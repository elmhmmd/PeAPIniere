<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        JWTAuth::factory()->setTTL(60); // Force integer TTL globally
    }
}