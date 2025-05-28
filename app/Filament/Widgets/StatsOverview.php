<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Material;
use Flowframe\Trend\Trend;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Cache;
use App\Filament\Resources\UserResource;
use Filament\Support\Enums\IconPosition;
use App\Filament\Resources\LessonResource;
use App\Filament\Resources\SectionResource;
use App\Filament\Resources\MaterialResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Tables\Columns\Summarizers\Values;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 3;

    protected function getStats(): array
    {
//          dd($value);
        return Cache::remember('stat', now()->addMinutes(60), function () {
        $data = Trend::model(User::class)
                ->between(
                    start: Carbon::now()->subMonths(2), // آخر 6 أشهر
                    end: Carbon::now()
                )
                ->perMonth()
                ->count()
                ->map(fn($value) => $value->aggregate)
                ->toArray()
                ;
                // dd($data);
        $student = User::whereHas('roles',
            fn($query) => $query->where('name','student'))
            ->count();
        $teacher = User::whereHas('roles',
            fn($query) => $query->where('name','teacher'))
            ->count();
        $allUser = $student + $teacher;

        $section = Section::pluck('name');
        return [
            Stat::make('All User',$allUser)
                ->description("Student: {$student},  Teacher: {$teacher}")
                ->chart($data)
                ->Color('#973da7')
                ->icon('heroicon-o-users')
                ->url(UserResource::getUrl())
                ,
            //     ->description("Teacher: {$teacher}")
            //     ->descriptionIcon('heroicon-o-user-group', IconPosition::Before),
            Stat::make('Number of Sections',Section::count())
                ->description($section->implode(', '))
                ->color('#973da7')
                ->url(SectionResource::getUrl()),
            stat::make('Number of Materials',Material::count())
                ->url(MaterialResource::getUrl()),
            stat::make('Number of Lessons',Lesson::count())
                ->url(LessonResource::getUrl()),
        ];
    });
    }
}
