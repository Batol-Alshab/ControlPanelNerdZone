<?php

namespace App\Filament\Student\Resources\MaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Student\Resources\MaterialResource;
use App\Filament\Student\Widgets\StudentStatsOverMaterialview;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
    
}
