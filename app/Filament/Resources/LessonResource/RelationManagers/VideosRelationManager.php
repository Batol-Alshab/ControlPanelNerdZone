<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class VideosRelationManager extends RelationManager
{
    protected static string $relationship = 'videos';
    protected static ?string $icon = 'heroicon-o-video-camera';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('messages.video.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.video.singular');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('messages.name'))
                    ->required(),
                FileUpload::make('video')
                    ->label(__('messages.video.label'))
                    ->required()
                    ->disk('public')->directory('Video')
                    ->maxSize(102400)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                // TextColumn::make('lesson.name')
                //     ->label('Lesson')
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->sortable()
                    ->date('Y M d')
                    ->toggleable(),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
