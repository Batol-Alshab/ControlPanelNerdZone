<?php

namespace App\Filament\Student\Resources\LessonResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Student\Resources\LessonResource;
use App\Filament\Student\Widgets\StudentStatsOverviewLesson;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    // protected static string $view = 'filament.student.resources.lesson-resource.pages.show';

    // يمكنك أيضا تجاوز getHeaderWidgets لإضافة إحصائيات
    protected function getHeaderWidgets(): array
    {
        return [
            StudentStatsOverviewLesson::class, // ويدجيت تعرض إحصائيات مثلاً
        ];
    }
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         StudentStatsOverviewLesson::class,
    //     ];
    // }
}
