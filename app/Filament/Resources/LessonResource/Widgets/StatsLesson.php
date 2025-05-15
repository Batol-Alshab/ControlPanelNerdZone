<?php

namespace App\Filament\Resources\LessonResource\Widgets;

use App\Models\Lesson;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsLesson extends BaseWidget
{
    protected static ?int $sort = -1;
    public ? Lesson  $record = null;
    protected function getStats(): array
    {
        if (!$this->record) {
            return [
                Stat::make('Number of Lessons', Lesson::count()),
            ];
        }
        return [
            Stat::make('Video for this Lesson', $this->record->videos()->count()),
            Stat::make('Summery for this Lesson', $this->record->summeries()->count()),
            Stat::make('Test for this Lesson', $this->record->tests()->count()),
            Stat::make('Course for this Lesson', $this->record->courses()->count()),
        ];
    }
}
