<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Inquiry;
use App\Models\Section;
use App\Models\Material;
use Flowframe\Trend\Trend;
use App\Models\UserMaterial;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Cache;
use App\Filament\Resources\UserResource;
use Filament\Support\Enums\IconPosition;
use App\Filament\Resources\LessonResource;
use App\Filament\Resources\InquiryResource;
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
        return Cache::remember('stat', now()->addMinutes(60), function () {
            $data = Trend::model(User::class)
                    ->between(
                        start: Carbon::now()->subDays(14),
                        end: Carbon::now()
                    )
                    ->perMonth()
                    ->count()
                    ->map(fn($value) => $value->aggregate)
                    ->toArray();

            $student = User::whereHas('roles',
                fn($query) => $query->where('name','student'))
                ->count();
            $teacher = User::whereHas('roles',
                fn($query) => $query->where('name','teacher'))
                ->count();
            $allUser = $student + $teacher;


            $joinUser24  = Trend::model(User::class)
                ->between(
                    start: Carbon::now()->subHour(24), // آخر 6 أشهر
                    end: Carbon::now()
                )
                ->perMonth()
                ->count()
                ->map(fn($value) => $value->aggregate);
            $description = $joinUser24[0]? 'increase' : '';
            $descriptionIcon = $joinUser24[0]? 'heroicon-m-arrow-trending-up' : '';

            $addInquiry24  = Trend::model(Inquiry::class)
                ->between(
                    start: Carbon::now()->subHour(24), // آخر 6 أشهر
                    end: Carbon::now()
                )
                ->perMonth()
                ->count()
                ->map(fn($value) => $value->aggregate);

            $materials = Material::get();
            foreach($materials as $material)
            {
                $materilaAccess[$material->name] = $material->users()
                            ->whereHas('roles', fn($query) => $query->where('id', 3))
                            ->count();
            }
            $numMaxMaxMaterilaAccess =max($materilaAccess);
            $nameMaxMaterilaAccess = array_search($numMaxMaxMaterilaAccess, $materilaAccess);
            return [
                Stat::make('All User',$allUser)
                    ->description("Student: {$student},  Teacher: {$teacher}")
                    ->chart($data)
                    ->icon('heroicon-o-users')
                    ->url(UserResource::getUrl()),

                stat::make('Join Users Last 24H ',$joinUser24[0])
                    ->icon('heroicon-o-user-plus')
                    ->description($description )
                    ->descriptionIcon($descriptionIcon)
                    ->url(UserResource::getUrl()),

                stat::make('Max Material Access',$nameMaxMaterilaAccess)
                    ->description($numMaxMaxMaterilaAccess)
                    ->icon('heroicon-o-question-mark-circle')
                    ->url(UserResource::getUrl()),

                stat::make('Add inquiry Last 24H ',$addInquiry24[0])
                    ->icon('heroicon-o-question-mark-circle')
                    ->url(InquiryResource::getUrl()),

            ];
    });
    }
}
