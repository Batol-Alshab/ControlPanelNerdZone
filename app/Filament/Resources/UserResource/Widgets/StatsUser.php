<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Illuminate\Foundation\Auth\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsUser extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('All User',User::count()),
        ];
    }
}
