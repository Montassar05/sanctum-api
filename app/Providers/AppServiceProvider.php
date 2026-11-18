<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
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
    }
}
