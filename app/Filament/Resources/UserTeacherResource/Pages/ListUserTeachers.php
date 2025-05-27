<?php

namespace App\Filament\Resources\UserTeacherResource\Pages;

use App\Filament\Resources\UserTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserTeachers extends ListRecords
{
    protected static string $resource = UserTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
