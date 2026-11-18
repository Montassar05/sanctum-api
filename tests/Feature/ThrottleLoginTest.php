<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThrottleLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_sixieme_tentative_bloquee(): void
    {
        $data = ['email' => 'pirate@test.fr', 'password' => 'x', 'device' => 'bot'];

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', $data)->assertUnauthorized();
        }

        $this->postJson('/api/login', $data)->assertTooManyRequests();
    }
}
