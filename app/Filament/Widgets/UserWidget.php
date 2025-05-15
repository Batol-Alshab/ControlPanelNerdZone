<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class UserWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart User Section';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return Cache::remember('userSection', now()->addMinutes(60), function () {
            $user = User::all();
            $scientific= $user->where('section_id',1)->count();
            $literary =$user->where('section_id',2)->count();
            return [
                'datasets'=>[
                    [
                        'label' =>'User',
                        'data' => [$scientific,$literary],
                        'backgroundColor' => ['#BA68C8','#d4a2dd'],
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => ['scientific','literary'],
            ];
         });
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
