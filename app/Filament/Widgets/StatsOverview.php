<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Material;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
//          $value = Cache::get('stat');
//          dd($value);
        return Cache::remember('stat', now()->addMinutes(1), function () {

        return [
            Stat::make('All User',User::count()),
            Stat::make('Number of Sections',Section::count()),
            stat::make('Number of Materials',Material::count()),
            stat::make('Number of Lessons',Lesson::count()),
        ];
    });
    }
}
