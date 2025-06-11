<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use Filament\Support\RawJs;
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
            $sections_name = Section::pluck('name');
            $materialCountInSections = Section::withCount('materials')->pluck('materials_count');

            return [
            'datasets' => [
                    [
                        'label' =>'Section',
                        'data' => $materialCountInSections,
                        'backgroundColor' => ['#c785d2','#d4a2dd'],
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => $sections_name,
            ];
        });
    }

    protected function getType(): string
    {
        return 'pie' ;
    }

    protected function getOptions():   RawJs
    {
        return RawJs::make(<<<'JS'
        {
            animation: {
                duration: 2000
            },
            interaction: {
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
            //     datalabels : {
            //     anchor : 'end',
            //     align : 'start',
            //     color : '#000',
            //     font : {
            //         'size' : 14,
            //         'weight' : 'bold',
            //     },
            // },
            },
            scales: {
                x: {
                    type: 'category',
                },
                y: {
                    grid:{
                        display:false,
                    },
                    ticks:{
                        display:false,
                    },
                },

            }
        }
    JS);
    }


}
