<?php

namespace App\Filament\Resources\SummeryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SummeryResource;

class CreateSummery extends CreateRecord
{
    protected static string $resource = SummeryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
