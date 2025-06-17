<?php

namespace App\Filament\Student\Resources\TestResource\Pages;

use App\Filament\Student\Resources\TestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTest extends CreateRecord
{
    protected static string $resource = TestResource::class;
}
