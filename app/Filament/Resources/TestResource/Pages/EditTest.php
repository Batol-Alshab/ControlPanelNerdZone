<?php

namespace App\Filament\Resources\TestResource\Pages;

use Filament\Actions;
use App\Filament\Resources\TestResource;
use Filament\Resources\Pages\EditRecord;

class EditTest extends EditRecord
{
    protected static string $resource = TestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }

    public function mount($record): void
    {
        parent::mount($record);
           if ($this->record) {
            $this->form->fill([
                'name' => $this->record->name,
                'lesson_id' => $this->record->lesson_id,
                'material' => $this->record->lesson?->material_id,
                'numQuestions' => $this->record->numQuestions,
                'is_complete' => $this->record->is_complete,
            ]);
        }
    }
}
