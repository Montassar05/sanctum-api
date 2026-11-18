<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MassAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_est_ignore_a_l_inscription(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Pirate',
            'email' => 'pirate@test.fr',
            'password' => 'secret123',
            'is_admin' => true,
        ])->assertCreated();

        $this->assertFalse(
            User::firstWhere('email', 'pirate@test.fr')->is_admin
        );
    }

    public function test_fillable_bloque_is_admin(): void
    {
        $user = User::create([
            'name' => 'Pirate',
            'email' => 'pirate2@test.fr',
            'password' => 'secret123',
            'is_admin' => true,
        ]);

        $this->assertFalse($user->fresh()->is_admin);
    }
}
