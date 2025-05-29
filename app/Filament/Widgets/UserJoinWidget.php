<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Flowframe\Trend\Trend;
use Filament\Support\RawJs;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class UserJoinWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart Join User';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        // return Cache::remember('userJoin',  now()->addMinutes(60), function () {
            $data6Month = Trend::model(User::class)
                ->between(
                    start: Carbon::now()->subMonths(6), // آخر 6 أشهر
                    end: Carbon::now()
                )
                ->perMonth()
                ->count();
            $data_last6Month = Trend::model(User::class)
                ->between(
                    start: Carbon::now()->subMonths(12), // آخر 6 أشهر
                    end: Carbon::now()->subMonth(6)
                )
                ->perMonth()
                ->count();
            // dd($data_last6Month->map(fn($value)=>$value->aggregate));
            $labels = $data6Month->merge($data_last6Month)
                    ->pluck('date')
                    ->sort()
                    ->unique()
                    ->values();

            return [
                'datasets' => [
                    [
                        'label' => 'Last 6 Months',
                        'data' => $labels->map(function($value) use($data6Month)
                        {
                            $founddate = $data6Month->firstWhere('date',$value);
                            return $founddate ? $founddate->aggregate :0;
                        }),
                        'borderColor' => '#BA68C8',
                    ],
                    [
                        'label' => 'User Joined befor Six months ago',
                        'data' => $labels->map(function($value) use ($data_last6Month)
                        {
                            $founddate = $data_last6Month->firstWhere('date',$value);
                            return $founddate ? $founddate->aggregate : 0;
                        }),
                        'borderColor' => '#a08357',
                    ],
                ],
                'labels' => $labels,

            ];
        // });
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions():   RawJs
    {
        return RawJs::make(<<<'JS'
        {
            animation: {
                duration: 5000
            },
            interaction: {
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    type: 'category'
                }
            }
        }
    JS);
    }

}
