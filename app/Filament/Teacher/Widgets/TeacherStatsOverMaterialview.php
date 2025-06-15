<?php

namespace App\Filament\Teacher\Widgets;


use App\Models\Lesson;
use App\Models\Material;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TeacherStatsOverMaterialview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 3;

    protected function getStats(): array
    {
        return Cache::remember('statmaterialForTeacher_'.app()->getLocale(), now()->addMinutes(60), function () {

            $user = auth()->user();
            $access_material_id =$user->materials()->pluck('material_id');
            $materialAll = Material::all();
            $materials = $materialAll->whereIn('id',$access_material_id);

            
            // dd($materialAll->users()->count);

            //لازم اعمل ستيت بعدد المواد
            foreach($materials as $material)
            {
                $stats[] = stat::make(__('messages.material.singular'),$material->name)
                ->description(__('messages.number of student can acsess').': '.$material->users()->count()-1)
                ;
            }
            return
                $stats;
        }
); }
}
