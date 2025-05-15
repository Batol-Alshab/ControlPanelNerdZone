<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class VideosRelationManager extends RelationManager
{
    protected static ?string $icon = 'heroicon-o-video-camera';

    protected static string $relationship = 'videos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                FileUpload::make('video')//->required()
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
                    ->toggleable(),
                TextColumn::make('name'),
                TextColumn::make('lesson.name')
                    ->label('Lesson')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->date('Y M d')
                    ->toggleable(),
                TextColumn::make('updated_at')
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
