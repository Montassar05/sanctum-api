<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LazyLoadingTest extends TestCase
{
    use RefreshDatabase;

    public function test_n_plus_1_est_bloque(): void
    {
        User::factory(3)->create();

        $this->expectException(LazyLoadingViolationException::class);

        foreach (User::all() as $user) {
            $user->tokens->count();
        }
    }

    public function test_with_charge_tout_en_2_requetes(): void
    {
        User::factory(3)->create()->each(fn ($u) => $u->createToken('app'));

        DB::enableQueryLog();
        foreach (User::with('tokens')->get() as $user) {
            $user->tokens->count();
        }

        $this->assertCount(2, DB::getQueryLog());
    }
}
