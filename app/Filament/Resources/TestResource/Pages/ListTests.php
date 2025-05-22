<?php

namespace App\Filament\Resources\TestResource\Pages;

use Filament\Actions;
use App\Filament\Resources\TestResource;
use Filament\Resources\Pages\ListRecords;

class ListTests extends ListRecords
{
    protected static string $resource = TestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
