<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_forms(): void
    {
        $this->get('/forms')->assertRedirect('/login');
        $this->get('/forms/create')->assertRedirect('/login');
    }

    public function test_user_can_view_forms_index(): void
    {
        $user = User::factory()->create();
        Form::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('/forms')
            ->assertOk()
            ->assertViewIs('forms.index')
            ->assertViewHas('forms');
    }

    public function test_user_can_create_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/forms', [
                'name' => 'Contact Form',
                'email_notifications' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('forms', [
            'user_id' => $user->id,
            'name' => 'Contact Form',
        ]);
    }

    public function test_form_name_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/forms', [
                'name' => '',
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_user_can_view_own_form(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get("/forms/{$form->id}")
            ->assertOk()
            ->assertViewIs('forms.show');
    }

    public function test_user_cannot_view_other_users_form(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user)
            ->get("/forms/{$form->id}")
            ->assertForbidden();
    }

    public function test_user_can_update_form(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->put("/forms/{$form->id}", [
                'name' => 'Updated Form Name',
                'email_notifications' => false,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('forms', [
            'id' => $form->id,
            'name' => 'Updated Form Name',
        ]);
    }

    public function test_user_can_delete_form(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete("/forms/{$form->id}")
            ->assertRedirect('/forms');

        $this->assertDatabaseMissing('forms', ['id' => $form->id]);
    }

    public function test_user_can_generate_api_key(): void
    {
        $user = User::factory()->create();
        $form = Form::factory()->create(['user_id' => $user->id]);

        $this->assertNull($form->api_key);

        $this->actingAs($user)
            ->post("/forms/{$form->id}/api-key")
            ->assertRedirect();

        $form->refresh();
        $this->assertNotNull($form->api_key);
        $this->assertEquals(64, strlen($form->api_key));
    }
}
