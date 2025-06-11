<?php

namespace App\Filament\Student\Resources\LessonResource\Pages;

use Filament\Actions;
use App\Models\Material;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Student\Resources\LessonResource;
use App\Filament\Student\Widgets\StudentStatsOverviewLesson;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    // protected static string $view = 'filament.student.resources.lesson-resource.pages.show';

    // يمكنك أيضا تجاوز getHeaderWidgets لإضافة إحصائيات
    protected function getFooterWidgets(): array
    {
        return [
            StudentStatsOverviewLesson::class, // ويدجيت تعرض إحصائيات مثلاً
        ];
    }

    public function getTabs(): array
    {
        $user =auth()->user();
        $accessMaterials_id = $user->materials()->pluck('material_id');
        $materials = Material::whereIn('id', $accessMaterials_id)->get();

        $tabs['all'] = Tab::make();
        foreach ($materials as $material)
            {
                $tabs[$material->name] = Tab::make()
                    ->modifyQueryUsing(fn($query) => $query->where('material_id',$material->id));
            }
        return $tabs;
    }
}
