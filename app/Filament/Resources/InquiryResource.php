<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Test;
use Filament\Tables;
use App\Models\Video;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Inquiry;
use App\Models\Summery;
use Filament\Forms\Form;
use Pest\Laravel\options;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use phpDocumentor\Reflection\Types\Self_;
use Filament\Forms\Components\MorphToSelect;
use App\Filament\Resources\InquiryResource\Pages;
use Filament\Forms\Components\MorphToSelect\Type;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InquiryResource\RelationManagers\AnswerRelationManager;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('inquiry')->required(),
                Select::make('user_id')->required()
                    ->relationship('user','name'),
                MorphToSelect::make('inquiryable')
                    ->types([
                        Type::make(Summery::class)->titleAttribute('name'),
                        Type::make(Video::class)->titleAttribute('name'),
                        Type::make(Test::class)->titleAttribute('name'),
                        Type::make(Course::class)->titleAttribute('name')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable(),
                // TextColumn::make('inquiryable.name')
                //     ->toggleable(),
                // TextColumn::make('inquiryable')
                //     ->formatStateUsing(fn() => Lesson::where('id','inquiryable.lesson_id')
                //     )
                    
                    // ->toggleable(),
                TextColumn::make('inquiry'),
                TextColumn::make('user.id'),
                TextColumn::make('user.name')
                    ->sortable(),
                // TextColumn::make('inquiryable_id')
                //     ->sortable(),
                TextColumn::make('inquiryable_type')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $extracted = basename($state))
                    ->badge()
                    ->color(function ($state) : string {
                    $extracted = basename($state);
                    return match($extracted){
                            'Summery' => 'success',
                            'Video' => 'info',
                            'Course' => 'gray',
                            'Test' =>'primary',
                    };
                }

                    //  $state ? 'success' : 'danger'
                     )
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->relationship('user','name'),
                SelectFilter::make('inquiryable_type')
                    ->options([
                        'App\Models\Summery'=> 'Summery',
                        'App\Models\Video'=> 'Video',
                        'App\Models\Test'=> 'Test',
                        'App\Models\Course'=> 'Course',
                    ])
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AnswerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
