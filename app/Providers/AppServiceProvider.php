<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Material;
use App\Policies\UserPolicy;
use App\Policies\LessonPolicy;
use App\Policies\MaterialPolicy;
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
        Lesson:: class => LessonPolicy:: class,
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
