<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_requires_authentication(): void
    {
        $this->getJson('/api/form')
            ->assertUnauthorized()
            ->assertJson(['error' => 'API key is required.']);
    }

    public function test_api_rejects_invalid_key(): void
    {
        $this->getJson('/api/form', [
            'Authorization' => 'Bearer invalid-key',
        ])
            ->assertUnauthorized()
            ->assertJson(['error' => 'Invalid API key.']);
    }

    public function test_can_get_form_info_with_valid_api_key(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();

        $this->getJson('/api/form', [
            'Authorization' => "Bearer {$form->api_key}",
        ])
            ->assertOk()
            ->assertJsonStructure([
                'form' => [
                    'id',
                    'name',
                    'endpoint',
                    'endpoint_url',
                    'is_active',
                    'submissions_count',
                    'created_at',
                ],
            ]);
    }

    public function test_can_use_x_api_key_header(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();

        $this->getJson('/api/form', [
            'X-API-Key' => $form->api_key,
        ])->assertOk();
    }

    public function test_can_get_submissions(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();
        Submission::factory()->count(5)->create(['form_id' => $form->id]);

        $this->getJson('/api/submissions', [
            'Authorization' => "Bearer {$form->api_key}",
        ])
            ->assertOk()
            ->assertJsonStructure([
                'submissions',
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ])
            ->assertJsonCount(5, 'submissions');
    }

    public function test_can_filter_submissions_by_spam(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();
        Submission::factory()->count(3)->create(['form_id' => $form->id, 'is_spam' => false]);
        Submission::factory()->count(2)->spam()->create(['form_id' => $form->id]);

        // Default excludes spam
        $this->getJson('/api/submissions', [
            'Authorization' => "Bearer {$form->api_key}",
        ])->assertJsonCount(3, 'submissions');

        // Include only spam
        $this->getJson('/api/submissions?spam=1', [
            'Authorization' => "Bearer {$form->api_key}",
        ])->assertJsonCount(2, 'submissions');
    }

    public function test_can_get_single_submission(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();
        $submission = Submission::factory()->create(['form_id' => $form->id]);

        $this->getJson("/api/submissions/{$submission->id}", [
            'Authorization' => "Bearer {$form->api_key}",
        ])
            ->assertOk()
            ->assertJsonStructure([
                'submission' => [
                    'id',
                    'data',
                    'files',
                    'is_spam',
                    'is_read',
                    'ip_address',
                    'user_agent',
                    'referrer',
                    'created_at',
                ],
            ]);
    }

    public function test_can_delete_submission_via_api(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();
        $submission = Submission::factory()->create(['form_id' => $form->id]);

        $this->deleteJson("/api/submissions/{$submission->id}", [], [
            'Authorization' => "Bearer {$form->api_key}",
        ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('submissions', ['id' => $submission->id]);
    }

    public function test_can_mark_submission_spam_via_api(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();
        $submission = Submission::factory()->create([
            'form_id' => $form->id,
            'is_spam' => false,
        ]);

        $this->postJson("/api/submissions/{$submission->id}/spam", [], [
            'Authorization' => "Bearer {$form->api_key}",
        ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $submission->refresh();
        $this->assertTrue($submission->is_spam);
    }

    public function test_can_unmark_submission_spam_via_api(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();
        $submission = Submission::factory()->spam()->create(['form_id' => $form->id]);

        $this->deleteJson("/api/submissions/{$submission->id}/spam", [], [
            'Authorization' => "Bearer {$form->api_key}",
        ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $submission->refresh();
        $this->assertFalse($submission->is_spam);
    }

    public function test_can_get_stats(): void
    {
        $form = Form::factory()->create();
        $form->generateApiKey();
        Submission::factory()->count(5)->create(['form_id' => $form->id]);
        Submission::factory()->count(2)->spam()->create(['form_id' => $form->id]);

        $this->getJson('/api/form/stats', [
            'Authorization' => "Bearer {$form->api_key}",
        ])
            ->assertOk()
            ->assertJsonStructure([
                'stats' => [
                    'total_submissions',
                    'spam_submissions',
                    'unread_submissions',
                    'daily_submissions',
                ],
            ])
            ->assertJsonPath('stats.total_submissions', 7)
            ->assertJsonPath('stats.spam_submissions', 2);
    }

    public function test_cannot_access_other_forms_submissions(): void
    {
        $form1 = Form::factory()->create();
        $form1->generateApiKey();

        $form2 = Form::factory()->create();
        $submission = Submission::factory()->create(['form_id' => $form2->id]);

        $this->getJson("/api/submissions/{$submission->id}", [
            'Authorization' => "Bearer {$form1->api_key}",
        ])->assertNotFound();
    }
}
