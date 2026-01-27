<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@formet.io'],
            ['name' => 'Demo User', 'password' => 'password']
        );

        $contactForm = Form::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Contact Form'],
            ['email_notifications' => true, 'endpoint' => 'contact123']
        );

        $newsletterForm = Form::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Newsletter'],
            ['email_notifications' => true, 'endpoint' => 'newsletter1']
        );

        // Sample submissions
        if ($contactForm->submissions()->count() === 0) {
            Submission::create([
                'form_id' => $contactForm->id,
                'data' => ['name' => 'John Doe', 'email' => 'john@example.com', 'message' => 'I love your product!'],
                'ip_address' => '192.168.1.100',
            ]);
            Submission::create([
                'form_id' => $contactForm->id,
                'data' => ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'message' => 'Can you call me back?', 'phone' => '555-1234'],
                'ip_address' => '192.168.1.101',
            ]);
            Submission::create([
                'form_id' => $contactForm->id,
                'data' => ['name' => 'Bob Wilson', 'email' => 'bob@example.com', 'message' => 'Great service, thanks!'],
                'ip_address' => '192.168.1.102',
            ]);
        }

        if ($newsletterForm->submissions()->count() === 0) {
            foreach (['alice@example.com', 'charlie@example.com', 'eve@example.com'] as $email) {
                Submission::create([
                    'form_id' => $newsletterForm->id,
                    'data' => ['email' => $email],
                    'ip_address' => '192.168.1.' . rand(1, 255),
                ]);
            }
        }
    }
}
