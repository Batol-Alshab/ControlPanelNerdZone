<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TestsRelationManager extends RelationManager
{
    protected static string $relationship = 'tests';
    protected static ?string $icon = 'heroicon-o-bookmark';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),

                Select::make('numQuestions')->required()
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
