<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_redirects_to_home(): void
    {
        // In demo mode, login redirects to home which auto-logs in
        $this->get('/login')->assertRedirect('/');
    }

    public function test_register_page_redirects_to_home(): void
    {
        // In demo mode, register redirects to home which auto-logs in
        $this->get('/register')->assertRedirect('/');
    }

    public function test_home_auto_logs_in_demo_user(): void
    {
        $this->get('/')->assertRedirect('/dashboard');

        // Demo user should be created and logged in
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'demo@formet.io',
        ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $user = User::factory()->create();

        // Guest middleware redirects authenticated users to dashboard
        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect('/dashboard');
    }

    public function test_authenticated_user_is_redirected_from_register(): void
    {
        $user = User::factory()->create();

        // Guest middleware redirects authenticated users to dashboard
        $this->actingAs($user)
            ->get('/register')
            ->assertRedirect('/dashboard');
    }
}
