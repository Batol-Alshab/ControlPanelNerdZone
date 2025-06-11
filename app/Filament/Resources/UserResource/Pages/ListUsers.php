<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Models\Section;
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
    public function getTabs(): array{
        $sections = Section::all();
        $tabs['all'] = Tab::make();
        foreach($sections as $section)
        {
            $tabs[$section->name] =Tab::make()->modifyQueryUsing(
                fn($query) => $query->where('section_id',$section->id)
            );
        }

        return
            $tabs;

    }

}
