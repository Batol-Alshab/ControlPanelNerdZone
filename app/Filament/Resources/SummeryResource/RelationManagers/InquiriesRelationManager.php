<?php

namespace App\Filament\Resources\SummeryResource\RelationManagers;

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
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Resources\RelationManagers\RelationManager;

class InquiriesRelationManager extends RelationManager
{
    protected static string $relationship = 'inquiries';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('messages.inquiry.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.inquiry.plural');
    }
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
                TextColumn::make('inquiry')
                    ->label(__('messages.inquiry.label')),
                TextColumn::make('status')
                    ->label(__('messages.status'))
                    ->formatStateUsing(fn ($state) =>  match($state)
                    {
                            'No Answer' => __('messages.No Answer'),
                            'complete Answer' => __('messages.complete Answer'),
                            'ignorance' => __('messages.ignorance'),

                    })
                    ->badge()
                    ->color(fn ($state) => match($state)
                    {
                        'No Answer' => 'danger',
                        'complete Answer' => 'success',
                        'ignorance' => 'warning'
                    })
                    ->sortable(),
                TextColumn::make('answer.answer_content')
                    ->label(__('messages.answer.label')),
                TextColumn::make('user.name')
                    ->label(__('messages.name_student'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('messages.status'))
                    ->options([
                        'No Answer' => __('messages.No Answer'),
                        'complete Answer' => __('messages.complete Answer'),
                        'ignorance' => __('messages.ignorance')
                    ]),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // EditAction::make(),
                Action::make('createInquiryWithAnswer')
                    ->label(__('messages.add_answer'))
                    ->icon('heroicon-o-plus')
                    ->form([
                        Fieldset::make('Answer')
                            ->label(__('messages.answer.label'))
                            ->schema([
                                MarkdownEditor::make('inquiry')
                                    ->label(__('messages.inquiry.label'))
                                    ->disabled()
                                    ->maxLength(255)
                                    ->default(fn (Inquiry $record) => $record->inquiry?? ''),
                                MarkdownEditor::make('answer_content')
                                    ->label(__('messages.answer.label'))
                                    ->maxLength(700)
                                    ->default(fn (Inquiry $record) => $record->answer?->answer_content ?? ''),
                                    ]),

                        Fieldset::make('status Answer')
                                    ->label(__('messages.status'))
                            ->schema([
                                Select::make('status')->required()
                                    ->label(__('messages.status'))
                                    ->options([
                                        'No Answer' => __('messages.No Answer'),
                                        'complete Answer' => __('messages.complete Answer'),
                                        'ignorance' => __('messages.ignorance')
                                    ])
                                    ->default(fn (Inquiry $record) => $record->status?? ''),
                            ]),
                    ])
                    ->action(fn(array $data, Inquiry $record) => $this->addAnswer($data, $record)),

                Action::make('delete')
                    ->label(__('messages.deldete_answer'))
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
