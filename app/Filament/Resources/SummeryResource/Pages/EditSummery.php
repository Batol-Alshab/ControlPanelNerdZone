<?php

namespace App\Filament\Resources\SummeryResource\Pages;

use App\Filament\Resources\SummeryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSummery extends EditRecord
{
    protected static string $resource = SummeryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
