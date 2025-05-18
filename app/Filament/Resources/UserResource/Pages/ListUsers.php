<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\UserResource\Widgets\StatsUser;
use App\Filament\Resources\UserResource\Widgets\UserStatsWidget;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            StatsUser::class,
        ];
    }
    public function getTabs(): array{
        return [
            'All' => Tab::make(),
            'scientific' => Tab::make()->modifyQueryUsing(function ( $query){
                $query->where('section_id',1);
            }),
            'literary' => Tab::make()->modifyQueryUsing(function ( $query){
                $query->where('section_id',2);
            }),
        ];
    }

}
