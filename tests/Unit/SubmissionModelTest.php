<?php

namespace Tests\Unit;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_email_field_finds_common_email_keys(): void
    {
        $form = Form::factory()->create();

        $submission = Submission::factory()->create([
            'form_id' => $form->id,
            'data' => ['email' => 'test@example.com'],
        ]);
        $this->assertEquals('test@example.com', $submission->getEmailField());

        $submission = Submission::factory()->create([
            'form_id' => $form->id,
            'data' => ['Email' => 'test2@example.com'],
        ]);
        $this->assertEquals('test2@example.com', $submission->getEmailField());

        $submission = Submission::factory()->create([
            'form_id' => $form->id,
            'data' => ['e-mail' => 'test3@example.com'],
        ]);
        $this->assertEquals('test3@example.com', $submission->getEmailField());
    }

    public function test_get_email_field_returns_null_when_not_found(): void
    {
        $submission = Submission::factory()->create([
            'data' => ['name' => 'John', 'message' => 'Hello'],
        ]);

        $this->assertNull($submission->getEmailField());
    }

    public function test_get_email_field_validates_email_format(): void
    {
        $submission = Submission::factory()->create([
            'data' => ['email' => 'not-an-email'],
        ]);

        $this->assertNull($submission->getEmailField());
    }

    public function test_get_name_field(): void
    {
        $form = Form::factory()->create();

        $submission = Submission::factory()->create([
            'form_id' => $form->id,
            'data' => ['name' => 'John Doe'],
        ]);
        $this->assertEquals('John Doe', $submission->getNameField());

        $submission = Submission::factory()->create([
            'form_id' => $form->id,
            'data' => ['full_name' => 'Jane Smith'],
        ]);
        $this->assertEquals('Jane Smith', $submission->getNameField());
    }

    public function test_has_files(): void
    {
        $submission = Submission::factory()->create(['files' => null]);
        $this->assertFalse($submission->hasFiles());

        $submission = Submission::factory()->withFiles()->create();
        $this->assertTrue($submission->hasFiles());
    }

    public function test_get_file_urls(): void
    {
        $submission = Submission::factory()->create(['files' => null]);
        $this->assertEmpty($submission->getFileUrls());

        $submission = Submission::factory()->withFiles([
            [
                'original_name' => 'test.pdf',
                'path' => 'submissions/1/test.pdf',
                'size' => 1024,
                'mime_type' => 'application/pdf',
            ],
        ])->create();

        $urls = $submission->getFileUrls();
        $this->assertCount(1, $urls);
        $this->assertEquals('test.pdf', $urls[0]['name']);
        $this->assertEquals(1024, $urls[0]['size']);
    }

    public function test_to_webhook_payload(): void
    {
        $submission = Submission::factory()->create([
            'data' => ['email' => 'test@example.com'],
            'ip_address' => '192.168.1.1',
            'referrer' => 'https://example.com',
        ]);

        $payload = $submission->toWebhookPayload();

        $this->assertEquals($submission->id, $payload['id']);
        $this->assertEquals($submission->form_id, $payload['form_id']);
        $this->assertEquals(['email' => 'test@example.com'], $payload['data']);
        $this->assertEquals('192.168.1.1', $payload['ip_address']);
        $this->assertEquals('https://example.com', $payload['referrer']);
        $this->assertArrayHasKey('submitted_at', $payload);
    }

    public function test_submission_belongs_to_form(): void
    {
        $form = Form::factory()->create();
        $submission = Submission::factory()->create(['form_id' => $form->id]);

        $this->assertEquals($form->id, $submission->form->id);
    }
}
