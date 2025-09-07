<?php

namespace App\Filament\Resources\TestResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Question;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    //اضافة /////////////////////////////
protected static ?string $icon = 'heroicon-o-question-mark-circle';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('messages.question.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.question.plural');
    }

    
    public function form(Form $form): Form
    {
        // $questionCount = $this->record ? $this->record->questions()->count() : 0;
        // $questionCount = Question::where('test_id', $this->id)->count();
        return $form
            ->schema([
                MarkdownEditor::make('content')
                    ->label(__('messages.question.label'))
                    ->required()->columnSpanFull(),
                // FileUpload::make('image')
                //     ->label(__('messages.image'))
                //     ->nullable()
                //     ->disk('public')->directory('Test-Image'),

                TextInput::make('option_1')
                    ->label(__('messages.The first option'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('option_2')
                    ->label(__('messages.The second option'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('option_3')
                    ->label(__('messages.The third option'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('option_4')
                    ->label(__('messages.The fourth option'))
                    ->required()
                    ->maxLength(255),

                Select::make('correct_option')
                    ->label(__('messages.correct_option'))
                    ->required()
                    ->options([
                        1=>__('messages.The first option'),
                        2=>__('messages.The second option'),
                        3=>__('messages.The third option'),
                        4=>__('messages.The fourth option'),
                    ]),

            ])
                // ->disabled()
            ;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                TextColumn::make('content')
                    ->label(__('messages.question.label')),
                // TextColumn::make('image')
                //     ->label(__('messages.image'))
                //     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('option_1')
                    ->label(__('messages.The first option')),
                TextColumn::make('option_2')
                    ->label(__('messages.The second option')),
                TextColumn::make('option_3')
                    ->label(__('messages.The third option')),
                TextColumn::make('option_4')
                    ->label(__('messages.The fourth option')),
                TextColumn::make('correct_option')
                    ->label(__('messages.correct_option')),
                // TextColumn::make('test.is_complete')

            ])
            ->emptyStateHeading(__('messages.no_questions'))
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
