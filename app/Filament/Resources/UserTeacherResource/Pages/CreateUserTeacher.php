<?php

namespace App\Filament\Resources\UserTeacherResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserTeacherResource;

class CreateUserTeacher extends CreateRecord
{
    protected static string $resource = UserTeacherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterCreate(): void
    {
        $this->record->assignRole('teacher');
    }
}

