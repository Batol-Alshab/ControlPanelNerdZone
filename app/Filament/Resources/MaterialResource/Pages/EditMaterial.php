<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MaterialResource;
use App\Filament\Resources\MaterialResource\Widgets\StatsMaterial;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            StatsMaterial::class,
        ];
    }
}
