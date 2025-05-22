<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Material;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\MaterialPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies=
    [
        User:: class => UserPolicy:: class,
        Material:: class => MaterialPolicy:: class,
        // Role:: class => RolePolicy:: class,
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
