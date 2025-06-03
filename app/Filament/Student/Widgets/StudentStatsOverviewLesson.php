<?php

namespace App\Filament\Student\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StudentStatsOverviewLesson extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('lesson','math'),
        ];
    }
}
