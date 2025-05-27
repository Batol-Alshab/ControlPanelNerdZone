<?php

namespace App\Filament\Resources\UserTeacherResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\UserTeacherResource;

class EditUserTeacher extends EditRecord
{
    protected static string $resource = UserTeacherResource::class;

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
}
