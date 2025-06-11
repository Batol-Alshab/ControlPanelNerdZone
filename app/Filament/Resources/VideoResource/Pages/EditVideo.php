<?php

namespace App\Filament\Resources\VideoResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VideoResource;

class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount($record): void
    {
// phpinfo();

//         public function mount()
// {
    // عرض الحجم الأقصى المسموح به
    // dd([
    //     'max_file_size' => ini_get('upload_max_filesize'),
    //     'post_max_size' => ini_get('post_max_size'),
    //     'memory_limit' => ini_get('memory_limit')
    // ]);
// }
        parent::mount($record);
           if ($this->record) {
            $this->form->fill([
                'name' => $this->record->name,
                'lesson_id' => $this->record->lesson_id,
                'material' => $this->record->lesson?->material_id,
                'video' => $this->record->video,
            ]);
        }
    }
}
