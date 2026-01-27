<?php

namespace Database\Factories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'form_id' => Form::factory(),
            'data' => [
                'name' => fake()->name(),
                'email' => fake()->email(),
                'message' => fake()->paragraph(),
            ],
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'referrer' => fake()->url(),
            'is_spam' => false,
            'is_read' => false,
        ];
    }

    public function spam(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_spam' => true,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }

    public function withFiles(array $files = null): static
    {
        return $this->state(fn (array $attributes) => [
            'files' => $files ?? [
                [
                    'field' => 'attachment',
                    'original_name' => 'document.pdf',
                    'path' => 'submissions/1/document.pdf',
                    'size' => 1024,
                    'mime_type' => 'application/pdf',
                ],
            ],
        ]);
    }

    public function newsletter(): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => [
                'email' => fake()->email(),
            ],
        ]);
    }

    public function contact(): static
    {
        return $this->state(fn (array $attributes) => [
            'data' => [
                'name' => fake()->name(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'message' => fake()->paragraph(),
            ],
        ]);
    }
}
