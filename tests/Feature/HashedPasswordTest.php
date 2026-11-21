<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HashedPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_mot_de_passe_est_hashe_automatiquement(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Ana', 'email' => 'ana@test.fr',
            'password' => 'secret123',
        ])->assertCreated();

        $user = User::firstWhere('email', 'ana@test.fr');

        $this->assertNotSame('secret123', $user->password);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }
}
