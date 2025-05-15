<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class UserJoinWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart Join User';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        return Cache::remember('Userjoin',  now()->addMinutes(60), function () {
            $data = Trend::model(User::class)
                ->between(
                    start: Carbon::now()->subMonths(6), // آخر 6 أشهر
                    end: Carbon::now()
                )
                ->perMonth()
                ->count();
            $data_last6Month = Trend::model(User::class)
                ->between(
                start: Carbon::now()->subMonths(9), // آخر 6 أشهر
                end: Carbon::now()->subMonth(3)
                )
                ->perMonth()
                ->count();
                // dd($data);
            return [
                'datasets' => [
                    [
                        'label' => 'Last 6 Months',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => '#BA68C8',
                    ],
                    [
                        'label' => 'User Joined befor Six months ago',
                        'data' => $data_last6Month->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => '#a08357',
                    ],
                ],
                'labels' => $data_last6Month->map(fn (TrendValue $value) => $value->date),
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }
}
