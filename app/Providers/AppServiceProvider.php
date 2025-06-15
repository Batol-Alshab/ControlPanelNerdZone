<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Material;
use App\Policies\UserPolicy;
use App\Policies\LessonPolicy;
use App\Policies\MaterialPolicy;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

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
    // public function boot(): void
    // {
    //     //
    // }
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            // Define supported languages
            $switch->locales(['en', 'ar']);

            // Optional: Set custom labels
            $switch->labels([
                'en' => 'English',
                'ar' => 'العربية'
            ]);
            $switch->visible(outsidePanels: true);

        });

        config()->set('app.url', 'http://' . request()->server('HTTP_HOST'));

        // تكوين نظام الملفات
        config()->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => '/storage',
            'visibility' => 'public',
            'throw' => false,
        ]);
    }
}
