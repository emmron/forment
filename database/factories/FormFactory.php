<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FormFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true) . ' Form',
            'endpoint' => Str::random(12),
            'email_notifications' => true,
            'notification_email' => null,
            'redirect_url' => null,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withWebhook(string $url = 'https://example.com/webhook'): static
    {
        return $this->state(fn (array $attributes) => [
            'webhook_enabled' => true,
            'webhook_url' => $url,
        ]);
    }

    public function withSlack(string $url = 'https://hooks.slack.com/services/xxx'): static
    {
        return $this->state(fn (array $attributes) => [
            'slack_enabled' => true,
            'slack_webhook_url' => $url,
        ]);
    }

    public function withRecaptcha(): static
    {
        return $this->state(fn (array $attributes) => [
            'captcha_type' => 'recaptcha_v3',
            'captcha_site_key' => 'test-site-key',
            'captcha_secret_key' => 'test-secret-key',
        ]);
    }

    public function withFileUploads(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_uploads_enabled' => true,
            'max_file_size_mb' => 10,
            'allowed_file_types' => ['pdf', 'jpg', 'png'],
        ]);
    }

    public function withDomainRestriction(array $domains): static
    {
        return $this->state(fn (array $attributes) => [
            'allowed_domains' => $domains,
        ]);
    }

    public function withAutoresponder(): static
    {
        return $this->state(fn (array $attributes) => [
            'autoresponder_enabled' => true,
            'autoresponder_subject' => 'Thank you for contacting us',
            'autoresponder_message' => 'Hi {{name}}, we received your message and will get back to you soon.',
        ]);
    }
}
