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
use App\Models\Material;
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
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\InquiryResource\Pages;
use Filament\Forms\Components\MorphToSelect\Type;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InquiryResource\RelationManagers\AnswerRelationManager;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function getNavigationLabel(): string
    {
        return __('messages.inquiry.navigation');
    }
    public static function getLabel(): ?string
    {
        return __('messages.inquiry.singular');
    }
    public static function getPluralLabel(): ?string
    {
        return __('messages.inquiry.plural');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MarkdownEditor::make('inquiry')
                    ->label(__('messages.inquiry.label'))
                    ->required()
                    ->disabledOn('edit'),
                Select::make('user_id')
                    ->label(__('messages.name_student'))
                    ->required()
                    ->disabledOn('edit')
                    ->relationship('user','name'),
                MorphToSelect::make('inquiryable')
                    ->label(__('messages.inquiryable'))
                    ->disabledOn('edit')
                    ->types([
                        Type::make(Summery::class)->titleAttribute('name')->label(__('messages.summery.singular')),
                        Type::make(Video::class)->titleAttribute('name')->label(__('messages.video.singular')),
                        Type::make(Test::class)->titleAttribute('name')->label(__('messages.test.singular')),
                        Type::make(Course::class)->titleAttribute('name')->label(__('messages.course.singular'))
                    ]),
                Select::make('status')
                    ->label(__('messages.status'))
                    ->hiddenOn('create')
                    ->required()
                    ->options([
                        'No Answer' => __('messages.No Answer'),
                        'complete Answer' => __('messages.complete Answer'),
                        'ignorance' => __('messages.ignorance')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        'ignorance' => 'warning',
                    })
                    ->sortable(),

                TextColumn::make('inquiryable_type')
                    ->label(__('messages.inquiryable'))
                    ->sortable()
                    ->formatStateUsing(fn ($state) => basename(__('messages.'.basename($state).'.label')))
                    // ->badge()
                    ->color(function ($state) : string {
                    $extracted = basename($state);
                    return match($extracted){
                            'Summery' => 'success',
                            'Video' => 'info',
                            'Course' => 'gray',
                            'Test' =>'primary',
                    };
                    }),
                    TextColumn::make('inquiryable.name')
                    ->label(__('messages.name'))
                    // ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label(__('messages.name_student'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('inquiryable_type')
                    ->label(__('messages.inquiryable'))
                    ->options([
                        'App\Models\Summery'=> __('messages.summery.singular'),
                        'App\Models\Video'=> __('messages.video.singular'),
                        'App\Models\Test'=> __('messages.test.singular'),
                        'App\Models\Course'=> __('messages.course.singular'),
                    ]),
                SelectFilter::make('status')
                    ->label(__('messages.status'))
                    ->options([
                        'No Answer' => __('messages.No Answer'),
                        'complete Answer' => __('messages.complete Answer'),
                        'ignorance' => __('messages.ignorance')
                    ]),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        $roleNames = $user->getRoleNames();

        if($roleNames->contains('admin'))
            return $query;

        $access_material_id =$user->materials()->pluck('material_id');
        $lessons = Lesson::whereIn('material_id',$access_material_id)->pluck('id');

        $summeries = Summery::whereIn('lesson_id', $lessons)->pluck('id');
        $tests = Test::whereIn('lesson_id', $lessons)->pluck('id');
        $videos = Video::whereIn('lesson_id', $lessons)->pluck('id');
        $courses = Course::whereIn('lesson_id', $lessons)->pluck('id');

        return $query->where(function ($q) use ($summeries, $tests, $videos, $courses) {
            $q->where(function ($q) use ($summeries) {
                $q->where('inquiryable_type', Summery::class)
                ->whereIn('inquiryable_id', $summeries);
            })->orWhere(function ($q) use ($tests) {
                $q->where('inquiryable_type', Test::class)
                ->whereIn('inquiryable_id', $tests);
            })->orWhere(function ($q) use ($videos) {
                $q->where('inquiryable_type', Video::class)
                ->whereIn('inquiryable_id', $videos);
            })->orWhere(function ($q) use ($courses) {
                $q->where('inquiryable_type', Course::class)
                ->whereIn('inquiryable_id', $courses);
            });
        });
    }
}
