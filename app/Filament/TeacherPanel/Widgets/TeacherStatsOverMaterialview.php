<?php

namespace App\Filament\TeacherPanel\Widgets;


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
        return Cache::remember('statmaterialForTeacher', now()->addMinutes(60), function () {

            $user = auth()->user();
            $access_material_id =$user->materials()->pluck('material_id');
            $materials = Material::whereIn('id',$access_material_id)->get();

            //لازم اعمل ستيت بعدد المواد
            foreach($materials as $material)
                $stats[] = stat::make('Material',$material->name)
                ->description(Lesson::where('material_id',$material->id)->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success');
            return
                $stats;
        }
); }
}
