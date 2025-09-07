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
        return Cache::remember('stat_' . app()->getLocale(), now()->addMinutes(60), function () {
            $data = Trend::model(User::class)
                ->between(
                    start: Carbon::now()->subDays(14),
                    end: Carbon::now()
                )
                ->perMonth()
                ->count()
                ->map(fn($value) => $value->aggregate)
                ->toArray();

            $student = User::whereHas(
                'roles',
                fn($query) => $query->where('name', 'student')
            )
                ->count();
            $teacher = User::whereHas(
                'roles',
                fn($query) => $query->where('name', 'teacher')
            )
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
            $description = $joinUser24[0] ? __('messages.increase') : '';
            $descriptionIcon = $joinUser24[0] ? 'heroicon-m-arrow-trending-up' : '';

            $addInquiry24  = Trend::model(Inquiry::class)
                ->between(
                    start: Carbon::now()->subHour(24), // آخر 6 أشهر
                    end: Carbon::now()
                )
                ->perMonth()
                ->count()
                ->map(fn($value) => $value->aggregate);

            $materials = Material::all();
            $materialPoints = [];
            foreach ($materials as $material) {
                $materialPoints[$material->name] = $material->users()
                    ->whereHas('roles', fn($query) => $query->where('id', 3)) // الطلاب فقط
                    ->withSum(['userMaterials' => fn($q) => $q->where('material_id', $material->id)], 'rate')
                    ->get()
                    ->sum('user_materials_sum_rate'); // مجموع النقاط لكل مادة
            }

            $maxPoints = max($materialPoints);
            $topMaterialName = array_search($maxPoints, $materialPoints);
            
            return [
                Stat::make(__('messages.All User'), $allUser)
                    ->description(__('messages.student.navigation') . ' :' . $student . ', ' . __('messages.teacher.navigation') . ' :' . $teacher)
                    ->chart($data)
                    ->icon('heroicon-o-users')
                    ->url(UserResource::getUrl()),

                stat::make(__('messages.Join Users Last 24H'), $joinUser24[0])
                    ->icon('heroicon-o-user-plus')
                    ->description($description)
                    ->descriptionIcon($descriptionIcon)
                    ->url(UserResource::getUrl()),

                Stat::make(__('messages.Material With Max Points'), $topMaterialName)
                    ->description($maxPoints)
                    ->icon('heroicon-o-arrow-trending-up')
                    ->url(UserResource::getUrl()),
                stat::make(__('messages.Add inquiry Last 24H'), $addInquiry24[0])
                    ->icon('heroicon-o-question-mark-circle')
                    ->url(InquiryResource::getUrl()),

            ];
        });
    }
}
