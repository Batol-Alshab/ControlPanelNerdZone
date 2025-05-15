<?php

namespace App\Filament\Resources\MaterialResource\Widgets;

use App\Models\Lesson;
use App\Models\Material;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsMaterial extends BaseWidget
{
    public ? Material  $record = null;
    protected function getStats(): array
    {
        if (!$this->record) {
            return [
                Stat::make('Number of Materials', Material::count()),
            ];
        }
        return [
            Stat::make('Lessons for this Material', $this->record->lessons()->count()),
        ];
    }
}
