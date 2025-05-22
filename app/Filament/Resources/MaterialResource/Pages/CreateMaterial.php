<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MaterialResource;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
