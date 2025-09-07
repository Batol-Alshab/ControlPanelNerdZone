<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Models\Material;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
    protected function afterCreate(): void
    {
        $this->record->assignRole('student');
        $materials = Material::where('section_id', $this->record->section_id)
            ->pluck('id');
        foreach ($materials as $m) {
            $this->record->materials()->attach($m, ['rate' => 10]);
            // dd(11);
        }
    }
    
}
