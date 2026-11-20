<?php

namespace App\Providers;

use App\Models\Invitation;
use App\Models\ShortUrl;
use App\Models\User;
use App\Policies\InvitationPolicy;
use App\Policies\ShortUrlPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Invitation::class, InvitationPolicy::class);
        Gate::policy(ShortUrl::class, ShortUrlPolicy::class);

        $blade = $this->app->make(BladeCompiler::class);
        $blade->anonymousComponentPath(resource_path('views/components'));
    }
}
