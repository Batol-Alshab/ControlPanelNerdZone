<?php

namespace App\Filament\Resources\SummeryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SummeryResource;

class ListSummeries extends ListRecords
{
    protected static string $resource = SummeryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
