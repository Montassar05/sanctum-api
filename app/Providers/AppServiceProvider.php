<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // En dev et en test : une requête N+1 lève une exception.
        Model::preventLazyLoading(! app()->isProduction());

        // L'admin passe toutes les Policies.
        // null = on laisse la Policy décider.
        Gate::before(fn (User $user) =>
            $user->is_admin ? true : null);
    }
}
