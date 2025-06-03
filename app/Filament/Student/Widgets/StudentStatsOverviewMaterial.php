<?php

namespace App\Filament\Student\Widgets;

use App\Models\Material;
use App\Filament\Resources\LessonResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StudentStatsOverviewMaterial extends BaseWidget
{
    protected function getStats(): array
    {
        // return Cache::remember('statmaterialForTeacher', now()->addMinutes(60), function () {

            $user = auth()->user();
            // $access_material_id =$user->materials()->pluck('material_id');
            // $materials = Material::whereIn('id',$access_material_id)->get();
            $materials = Material::where('section_id',$user->section_id)->get();
            //لازم اعمل ستيت بعدد المواد
            foreach($materials as $material)
                $stats[] = stat::make('',$material->name)
                ->url(LessonResource::getUrl())
                ->color('success');
            return
                $stats;
        }
// ); }

}
