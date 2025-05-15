<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {

        return [
            Stat::make('Number of Sections',Section::count())->color('#BA68C8')

        //     ->extraAttributes([
        // 'class' => 'border border-red-500 text-blue-600', // Tailwind CSS classes
    // ]),,
        ];
    }
}
