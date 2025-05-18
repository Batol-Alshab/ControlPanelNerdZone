<?php

namespace App\Filament\Resources\TestResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Question;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

        public  function canCreate(): bool
    {
        $record = static::getOwnerRecord();
        $numQuestions = $record->numQuestions;
        $countQuestions = $record->questions()->count();
        if($countQuestions == $numQuestions )
            {
                $record->is_complete =1;
                Notification::make()
                    ->title('اكتمل عدد الأسئلة!')
                    ->body('تم الوصول إلى الحد الأقصى لعدد الأسئلة لهذا الاختبار.')
                    ->duration(1500)
                    ->success()
                    ->send();
                // $this->redirect(request()->header('Referer'));
            }
        else
            {
                $record->is_complete =0;
                // $this->redirect(request()->header('Referer'));
            }
        // ? $countQuestions == $numQuestions : 0;
        $record->save();
        return $countQuestions < $numQuestions ;
    }

    public function form(Form $form): Form
    {
        // $questionCount = $this->record ? $this->record->questions()->count() : 0;
        // $questionCount = Question::where('test_id', $this->id)->count();
        return $form
            ->schema([
                TextInput::make('content')->required(),
                FileUpload::make('image')->nullable()
                    ->disk('public')->directory('Test-Image'),

                TextInput::make('option_1')->required()
                    ->label('The first option')
                    ->maxLength(255),
                TextInput::make('option_2')->required()
                    ->label('The second option')
                    ->maxLength(255),
                TextInput::make('option_3')->required()
                    ->label('The third  option')
                    ->maxLength(255),
                TextInput::make('option_4')->required()
                    ->label('The fourth  option')
                    ->maxLength(255),

                Select::make('correct_option')->required()
                    ->options([
                        1=>'The first option',
                        2=>'The second option',
                        3=>'The third option',
                        4=>'The fourth option',
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
                TextColumn::make('content'),
                TextColumn::make('image')
                    ->toggleable(),
                TextColumn::make('option_1'),
                TextColumn::make('option_2'),
                TextColumn::make('option_3'),
                TextColumn::make('option_4'),
                TextColumn::make('correct_option'),
                // TextColumn::make('test.is_complete')

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
