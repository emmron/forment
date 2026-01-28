<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('form_submission');
    }

    public function test_can_submit_to_active_form(): void
    {
        $form = Form::factory()->create(['is_active' => true]);

        $this->post("/f/{$form->endpoint}", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello!',
        ])->assertOk();

        $this->assertDatabaseHas('submissions', [
            'form_id' => $form->id,
        ]);

        $submission = Submission::where('form_id', $form->id)->first();
        $this->assertEquals('John Doe', $submission->data['name']);
        $this->assertEquals('john@example.com', $submission->data['email']);
    }

    public function test_cannot_submit_to_inactive_form(): void
    {
        $form = Form::factory()->inactive()->create();

        $this->post("/f/{$form->endpoint}", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ])->assertForbidden();

        $this->assertDatabaseMissing('submissions', [
            'form_id' => $form->id,
        ]);
    }

    public function test_submission_returns_json_when_requested(): void
    {
        $form = Form::factory()->create();

        $this->postJson("/f/{$form->endpoint}", [
            'email' => 'test@example.com',
        ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Form submitted successfully',
            ]);
    }

    public function test_submission_redirects_when_redirect_url_set(): void
    {
        $form = Form::factory()->create([
            'redirect_url' => 'https://example.com/thank-you',
        ]);

        $this->post("/f/{$form->endpoint}", [
            'email' => 'test@example.com',
        ])->assertRedirect('https://example.com/thank-you');
    }

    public function test_honeypot_marks_submission_as_spam(): void
    {
        $form = Form::factory()->create();

        $this->post("/f/{$form->endpoint}", [
            'email' => 'test@example.com',
            '_honeypot' => 'spam bot filled this',
        ])->assertOk();

        $submission = Submission::where('form_id', $form->id)->first();
        $this->assertTrue($submission->is_spam);
    }

    public function test_empty_submission_is_rejected(): void
    {
        $form = Form::factory()->create();
        RateLimiter::clear($form->getRateLimitKey() . ':127.0.0.1');

        $this->postJson("/f/{$form->endpoint}", [])
            ->assertStatus(400)
            ->assertJson(['error' => 'No form data received.']);
    }

    public function test_submission_from_disallowed_domain_is_rejected(): void
    {
        $form = Form::factory()->withDomainRestriction(['allowed.com'])->create();

        $this->postJson("/f/{$form->endpoint}", [
            'email' => 'test@example.com',
        ], [
            'Origin' => 'https://notallowed.com',
        ])->assertForbidden();
    }

    public function test_submission_from_allowed_domain_succeeds(): void
    {
        $form = Form::factory()->withDomainRestriction(['allowed.com'])->create();
        RateLimiter::clear($form->getRateLimitKey() . ':127.0.0.1');

        $this->postJson("/f/{$form->endpoint}", [
            'email' => 'test@example.com',
        ], [
            'Origin' => 'https://allowed.com',
        ])->assertOk();
    }

    public function test_user_can_view_submission(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);
        $submission = Submission::factory()->create(['form_id' => $form->id]);

        $this->actingAs($user)
            ->get("/forms/{$form->id}/submissions/{$submission->id}")
            ->assertOk()
            ->assertViewIs('submissions.show');

        // Should mark as read
        $submission->refresh();
        $this->assertTrue($submission->is_read);
    }

    public function test_user_cannot_view_other_users_submission(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $otherUser->id]);
        $submission = Submission::factory()->create(['form_id' => $form->id]);

        $this->actingAs($user)
            ->get("/forms/{$form->id}/submissions/{$submission->id}")
            ->assertForbidden();
    }

    public function test_user_can_delete_submission(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);
        $submission = Submission::factory()->create(['form_id' => $form->id]);

        $this->actingAs($user)
            ->delete("/forms/{$form->id}/submissions/{$submission->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('submissions', ['id' => $submission->id]);
    }

    public function test_user_can_mark_submission_as_spam(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);
        $submission = Submission::factory()->create([
            'form_id' => $form->id,
            'is_spam' => false,
        ]);

        $this->actingAs($user)
            ->post("/forms/{$form->id}/submissions/{$submission->id}/spam")
            ->assertRedirect();

        $submission->refresh();
        $this->assertTrue($submission->is_spam);
    }

    public function test_user_can_export_submissions_csv(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);
        Submission::factory()->count(3)->create(['form_id' => $form->id]);

        $response = $this->actingAs($user)->get("/forms/{$form->id}/export");
        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_user_can_export_submissions_json(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);
        Submission::factory()->count(3)->create(['form_id' => $form->id]);

        $response = $this->actingAs($user)->get("/forms/{$form->id}/export?format=json");
        $response->assertOk();
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
    }

    public function test_email_as_endpoint_auto_creates_form(): void
    {
        $email = 'test-auto@example.com';

        // First submission should create the form
        $this->postJson("/f/{$email}", [
            'name' => 'John Doe',
            'message' => 'Hello!',
        ])->assertOk()
          ->assertJson(['success' => true]);

        // Form should be created with email as endpoint
        $this->assertDatabaseHas('forms', [
            'endpoint' => $email,
            'notification_email' => $email,
            'email_notifications' => true,
            'user_id' => null,
        ]);

        // Submission should be stored
        $form = Form::where('endpoint', $email)->first();
        $this->assertDatabaseHas('submissions', [
            'form_id' => $form->id,
        ]);
    }

    public function test_email_endpoint_reuses_existing_form(): void
    {
        $email = 'reuse@example.com';

        // Create first submission
        $this->postJson("/f/{$email}", ['name' => 'First'])->assertOk();

        // Create second submission
        $this->postJson("/f/{$email}", ['name' => 'Second'])->assertOk();

        // Should only have one form
        $this->assertDatabaseCount('forms', 1);

        // Should have two submissions
        $form = Form::where('endpoint', $email)->first();
        $this->assertEquals(2, $form->submissions()->count());
    }
}
