<?php

namespace App\Filament\TeacherPanel\Widgets;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Material;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
//          $value = Cache::get('stat');
//          dd($value);
        // return Cache::remember('statmaterialForTeacher', now()->addMinutes(60), function () {

        $user = auth()->user();
        $permissionNames = $user->getPermissionNames();
        $materialNames = Material::whereIn('name',$permissionNames)->pluck('name');
        //لازم اعمل ستيت بعدد المواد
        foreach($materialNames as $materialName)
            $stats[] = stat::make('Material',$materialName)
            ->description('3% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        return
            $stats;
    }
// ); }
}
