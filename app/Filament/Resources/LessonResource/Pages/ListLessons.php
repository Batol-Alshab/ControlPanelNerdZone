<?php

namespace App\Filament\Resources\LessonResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LessonResource;
use App\Filament\Widgets\ContentLessonsChart;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            ContentLessonsChart::class,
        ];
    }
    // protected function getHeader(): array
    // {
    //     return [
    //         ContentLessonsChart::class,
    //     ];
    // }
    
}
