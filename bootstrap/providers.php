<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use Illuminate\Auth\AuthServiceProvider as LaravelAuthServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    LaravelAuthServiceProvider::class,
];
