<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\UserResource\Widgets\UserStatsWidget;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        $this->record->assignRole('teacher');
        return 'teacher';
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Teacher')->action(function()
            {Notification::make()
                ->title($this->getTitle())
                ->duration(5000)
                ->send();
            }

        ),
            Actions\DeleteAction::make(),
        ];
    }


}
