<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';
    protected static ?string $icon = 'heroicon-o-book-open';
    protected static ?string $title = 'Training';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),

                FileUpload::make('file')->required()
                    ->disk('public')->directory('SummeryFile')
                    ->maxSize(102400) // مثلاً 100MB
                    ->acceptedFileTypes(['application/pdf']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')
                    ->toggleable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                // TextColumn::make('lesson.name')
                //     ->label('Lesson')
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->date('Y M d')
                    ->toggleable(),
            ])
            ->filters([
                //
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
