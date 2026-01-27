<?php

namespace Tests\Unit;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_endpoint_on_creation(): void
    {
        $user = User::factory()->create();
        $form = Form::create([
            'user_id' => $user->id,
            'name' => 'Test Form',
        ]);

        $this->assertNotNull($form->endpoint);
        $this->assertEquals(12, strlen($form->endpoint));
    }

    public function test_endpoint_url_attribute(): void
    {
        $form = Form::factory()->create(['endpoint' => 'abc123']);

        $this->assertEquals(url('/f/abc123'), $form->endpoint_url);
    }

    public function test_generate_api_key(): void
    {
        $form = Form::factory()->create();

        $this->assertNull($form->api_key);

        $key = $form->generateApiKey();

        $this->assertNotNull($key);
        $this->assertEquals(64, strlen($key));
        $this->assertEquals($key, $form->fresh()->api_key);
    }

    public function test_get_notification_email_address(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        // Without notification_email set, uses user email
        $form = Form::factory()->create([
            'user_id' => $user->id,
            'notification_email' => null,
        ]);
        $this->assertEquals('user@example.com', $form->getNotificationEmailAddress());

        // With notification_email set, uses that
        $form->notification_email = 'custom@example.com';
        $this->assertEquals('custom@example.com', $form->getNotificationEmailAddress());
    }

    public function test_is_origin_allowed_with_no_restrictions(): void
    {
        $form = Form::factory()->create(['allowed_domains' => null]);

        $this->assertTrue($form->isOriginAllowed('https://example.com'));
        $this->assertTrue($form->isOriginAllowed('https://any-domain.com'));
        $this->assertTrue($form->isOriginAllowed(null));
    }

    public function test_is_origin_allowed_with_restrictions(): void
    {
        $form = Form::factory()->create([
            'allowed_domains' => ['example.com', 'trusted.org'],
        ]);

        $this->assertTrue($form->isOriginAllowed('https://example.com'));
        $this->assertTrue($form->isOriginAllowed('https://sub.example.com'));
        $this->assertTrue($form->isOriginAllowed('https://trusted.org'));
        $this->assertFalse($form->isOriginAllowed('https://evil.com'));
        $this->assertFalse($form->isOriginAllowed('https://notexample.com'));
    }

    public function test_get_smtp_config_returns_null_when_disabled(): void
    {
        $form = Form::factory()->create(['custom_smtp_enabled' => false]);

        $this->assertNull($form->getSmtpConfig());
    }

    public function test_get_smtp_config_returns_array_when_enabled(): void
    {
        $form = Form::factory()->create([
            'custom_smtp_enabled' => true,
            'smtp_host' => 'smtp.example.com',
            'smtp_port' => 587,
            'smtp_username' => 'user',
            'smtp_password' => 'pass',
            'smtp_encryption' => 'tls',
            'smtp_from_email' => 'from@example.com',
            'smtp_from_name' => 'Test Form',
        ]);

        $config = $form->getSmtpConfig();

        $this->assertIsArray($config);
        $this->assertEquals('smtp.example.com', $config['host']);
        $this->assertEquals(587, $config['port']);
    }

    public function test_get_allowed_file_types_default(): void
    {
        $form = Form::factory()->create(['allowed_file_types' => null]);

        $types = $form->getAllowedFileTypes();

        $this->assertContains('pdf', $types);
        $this->assertContains('jpg', $types);
        $this->assertContains('png', $types);
    }

    public function test_get_allowed_file_types_custom(): void
    {
        $form = Form::factory()->create([
            'allowed_file_types' => ['pdf', 'doc'],
        ]);

        $types = $form->getAllowedFileTypes();

        $this->assertEquals(['pdf', 'doc'], $types);
    }

    public function test_rate_limit_key(): void
    {
        $form = Form::factory()->create();

        $this->assertEquals("form_submission:{$form->id}", $form->getRateLimitKey());
    }

    public function test_form_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $form->user->id);
    }

    public function test_form_has_many_submissions(): void
    {
        $form = Form::factory()->create();

        $this->assertCount(0, $form->submissions);
    }
}
