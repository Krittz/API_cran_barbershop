<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [ // <-- Correção aqui
        User::class => UserPolicy::class,
    ];

    public function boot(): void {}
}
