<?php

namespace App\Filament\Resources\LessonResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\LessonResource;
use App\Filament\Resources\LessonResource\Widgets\StatsLesson;
use App\Filament\Resources\LessonResource\Widgets\LessonWidget;

class EditLesson extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         StatsLesson::class,
    //     ];
    // }

    // protected function getFooterWidgets(): array
    // {
    //     return [
    //         LessonWidget::class,
    //     ];
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    public function mount($record): void
    {
        parent::mount($record);

        // الآن يمكن استخدام $this->record وهو كائن Lesson
        if ($this->record->material) {
            $this->form->fill([
                'name' => $this->record->name, // كل الحقول مثل name, material_id, ...
                'section' => $this->record->material?->section_id, // نُضيف حقل section المشتق
            ]);

        }
    }

}
