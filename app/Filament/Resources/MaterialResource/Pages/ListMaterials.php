<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MaterialResource;
use App\Filament\Resources\MaterialResource\Widgets\StatsMaterial;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];

    }

}
