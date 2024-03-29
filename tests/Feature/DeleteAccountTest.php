<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Usuario;
use Laravel\Jetstream\Features;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_accounts_can_be_deleted()
    {
        if (! Features::hasAccountDeletionFeatures()) {
            return $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = Usuario::factory()->create());

        $component = Livewire::test(DeleteUserForm::class)
                        ->set('password', 'password')
                        ->call('deleteUser');

        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_before_account_can_be_deleted()
    {
        if (! Features::hasAccountDeletionFeatures()) {
            return $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = Usuario::factory()->create());

        Livewire::test(DeleteUserForm::class)
                        ->set('password', 'wrong-password')
                        ->call('deleteUser')
                        ->assertHasErrors(['password']);

        $this->assertNotNull($user->fresh());
    }
}
