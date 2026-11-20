<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::if('can', function ($ability, ...$arguments) {
            return Gate::allows($ability, ...$arguments);
        });

        Blade::if('cannot', function ($ability, ...$arguments) {
            return Gate::denies($ability, ...$arguments);
        });

        Blade::if('any', function ($abilities, ...$arguments) {
            return Gate::any($abilities, ...$arguments);
        });

        Blade::if('none', function ($abilities, ...$arguments) {
            return Gate::none($abilities, ...$arguments);
        });
    }
}
