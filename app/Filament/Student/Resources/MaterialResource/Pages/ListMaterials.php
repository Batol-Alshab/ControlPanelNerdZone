<?php

namespace App\Filament\Student\Resources\MaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Student\Resources\MaterialResource;
use App\Filament\Resources\RoleResource\Pages\ListRoles;
use App\Filament\Student\Widgets\StudentStatsOverviewMaterial;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            StudentStatsOverviewMaterial::class,
        ];
    }
}
