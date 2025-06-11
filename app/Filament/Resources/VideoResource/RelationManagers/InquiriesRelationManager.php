<?php

namespace App\Filament\Resources\VideoResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Answer;
use App\Models\Inquiry;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Resources\RelationManagers\RelationManager;

class InquiriesRelationManager extends RelationManager
{
    protected static string $relationship = 'inquiries';

    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Fieldset::make('Inquiry')
    //                 ->schema([
    //                     MarkdownEditor::make('inquiry')
    //                             // ->disabled()
    //                             ->maxLength(255),
    //                     Select::make('status')->required()
    //                         ->options([
    //                             'No Answer' => 'No Answer',
    //                             'complete Answer' => 'complete Answer',
    //                             'ignorance' => 'ignorance'
    //                         ]),
    //                 ]),
    //             Fieldset::make('Answer')
    //                 ->schema([
    //                     MarkdownEditor::make('answer.answer_content')
    //                             ->maxLength(700),
    //                 ]),

    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('inquiry')
            ->columns([
                // TextColumn::make('id'),
                TextColumn::make('inquiry'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match($state)
                    {
                        'No Answer' => 'danger',
                        'complete Answer' => 'success',
                        'ignorance' => 'warning'
                    })
                    ->sortable(),
                TextColumn::make('answer.answer_content'),
                TextColumn::make('user.name')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'No Answer' => 'No Answer',
                        'complete Answer' => 'complete Answer',
                        'ignorance' => 'ignorance'
                    ]),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // EditAction::make(),
                Action::make('createInquiryWithAnswer')
                    ->label('Answer')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Fieldset::make('Answer')
                            ->schema([
                                MarkdownEditor::make('inquiry')
                                    ->disabled()
                                    ->maxLength(255)
                                    ->default(fn (Inquiry $record) => $record->inquiry?? ''),
                                MarkdownEditor::make('answer_content')
                                    ->label('Answer Content')
                                    ->maxLength(700)
                                    ->default(fn (Inquiry $record) => $record->answer?->answer_content ?? ''),
                                    ]),

                        Fieldset::make('status Answer')
                            ->schema([
                                Select::make('status')->required()
                                    ->options([
                                        'No Answer' => 'No Answer',
                                        'complete Answer' => 'complete Answer',
                                        'ignorance' => 'ignorance'
                                    ])
                                    ->default(fn (Inquiry $record) => $record->status?? ''),
                            ]),
                    ])
                    ->action(fn(array $data, Inquiry $record) => $this->addAnswer($data, $record)),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->disabled(fn (Inquiry $record) => !$record->answer) // إخفاء الزر إذا لم تكن هناك إجابة
                    ->action(fn(Inquiry $record) => $record->answer()->delete()),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }


    public function addAnswer(array $data, Inquiry $record)
    {
        if($data['status'])
        {
            $record->update([
                'status' => $data['status']
            ]);
        }
        if($data['answer_content'])
        {
            $answer = Answer::where('inquiry_id', $record->id)->first();
            if($answer)
            {
                $answer->update([
                    'answer_content' => $data['answer_content'],
                ]);
            }
            else
            {
                $answer = Answer::create([
                    'answer_content' => $data['answer_content'],
                    'inquiry_id' => $record->id,
                ]);
            }
        }
    }

}
