<?php

namespace App\Filament\Resources\InquiryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class AnswerRelationManager extends RelationManager
{
    protected static string $relationship = 'answer';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('messages.answer.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.answer.singular');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('answer_content')
                    ->label(__('messages.answer.singular'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('answer_content')
            ->columns([
                TextColumn::make('answer_content')
                    ->label(__('messages.answer.singular')),
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
