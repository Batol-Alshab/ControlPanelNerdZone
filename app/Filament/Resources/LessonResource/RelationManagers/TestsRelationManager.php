<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TestsRelationManager extends RelationManager
{
    protected static string $relationship = 'tests';
    protected static ?string $icon = 'heroicon-o-bookmark';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('messages.test.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.test.singular');
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('messages.name'))
                    ->required(),

                Select::make('numQuestions')
                    ->label(__('messages.numQuestions'))
                    ->required()
                    ->options([
                        5=>5,
                        10=>10,
                        15=>15,
                        20=>20
                    ]),
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
                TextColumn::make('is_complete')
                    ->label(__('messages.status'))
                    ->formatStateUsing( fn ($state) => $state ? __('messages.is_complete') : __('messages.Not_complete'))
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->sortable()
                    ->date('Y M d')
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_complete')
                    ->label(__('messages.is_complete')),
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
