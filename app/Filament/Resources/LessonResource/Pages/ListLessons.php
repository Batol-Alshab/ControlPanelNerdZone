<?php

namespace App\Filament\Resources\LessonResource\Pages;

use Filament\Actions;
use App\Models\Material;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LessonResource;
use App\Filament\Widgets\ContentLessonsChart;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\LessonResource\Widgets\StatsLesson;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        $user =auth()->user();
        $roleNames = $user->getRoleNames();
        if ($roleNames->contains('admin'))
        {
            $materials = Material::all();
        }
        else
        {
            $accessMaterials_id = $user->materials()->pluck('material_id');
            $materials = Material::whereIn('id', $accessMaterials_id)->get();
        }

        $tabs['all'] = Tab::make();
        foreach ($materials as $material)
            {
                $tabs[$material->name] = Tab::make()
                    ->modifyQueryUsing(fn($query) => $query->where('material_id',$material->id));
            }
        return $tabs;
    }


}
