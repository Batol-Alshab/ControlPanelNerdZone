<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MaterialResource;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\MaterialResource\Widgets\StatsMaterial;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];

    }
    protected function getFooterWidgets(): array
    {
        return [
            StatsMaterial::class,
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
