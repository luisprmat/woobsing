<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use Laravel\Jetstream\Features;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = Usuario::factory()->withPersonalTeam()->create();

        $response = $this->actingAs($user)->get('/user/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/user/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/user/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
