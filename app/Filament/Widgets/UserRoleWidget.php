<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class UserRoleWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart User Role';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    protected function getCachedData(): array
    {
        return $this->cachedData ??= $this->getData();
    }

    protected function getData(): array
    {
        return Cache::remember('userRole', now()->addMinutes(60), function () {
            $teacher= User::whereHas('roles',fn($query) => $query->where('name','teacher'))
                ->count();
            $student = User::whereHas('roles',fn($query) => $query->where('name','student'))
                ->count();
            return [
                'datasets'=>[
                    [
                        'label' =>'User',
                        'data' => [$teacher,$student],
                        // 'data' => [$scientific,$literary],
                        // 'backgroundColor' => ['#BA68C8','#d4a2dd'],
                        'backgroundColor' => ['#973da7','#d4a0dc'],
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => ['teachers','students'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
