<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\UserMaterial;
use Filament\Widgets\LineChartWidget;

// class SectionWidget extends LineChartWidget
// {
    // protected static ?string $heading = 'تطور أداء الطلاب الشهري';
    // protected static ?int $sort = 20;

    // protected function getData(): array
    // {
    //     return Trend::model(UserMaterial::class)
    //         ->between(
    //             start: now()->subMonths(6),
    //             end: now()
    //         )
    //         ->perMonth()
    //         ->sum('rate') // مجموع نقاط الطلاب
    //         ->map(fn($value) => $value->aggregate)
    //         ->toArray();
    // }
// }
