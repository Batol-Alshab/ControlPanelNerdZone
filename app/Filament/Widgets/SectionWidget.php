<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class SectionWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart material Section';

    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 1;
    protected function getData(): array
    {
        return Cache::remember('materialSection', now()->addMinutes(60), function () {
            $sections_name = Section::all();
            $sections = Section::withCount('materials')->get();

            return [
            'datasets' => [
                    [
                        'label' =>'Section',
                        'data' => $sections->pluck('materials_count'),
                        'backgroundColor' => ['#c785d2','#d4a2dd'],
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => $sections_name->pluck('name'),
            ];
        });
    }

    protected function getType(): string
    {
        return 'pie' ;
    }

}
