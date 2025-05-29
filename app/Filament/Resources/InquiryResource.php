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
                    ]),
                Select::make('status')->required()
                    ->visibleOn('edit')
                    ->options([
                        'No Answer' => 'No Answer',
                        'complete Answer' => 'complete Answer',
                        'ignorance' => 'ignorance'
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
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match($state)
                    {
                        'No Answer' => 'danger',
                        'complete Answer' => 'success',
                        'ignorance' => 'warning'
                    })
                    ->sortable(),

                TextColumn::make('inquiryable.name')
                    // ->sortable()
                    ->searchable(),
                TextColumn::make('inquiryable_type')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $extracted = basename($state))
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
                TextColumn::make('user.name')
                    ->sortable(),
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
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'No Answer' => 'No Answer',
                        'complete Answer' => 'complete Answer',
                        'ignorance' => 'ignorance'
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
        $access_material_id =$user->materials()->pluck('material_id');


        if($roleNames->contains('admin'))
            return $query;

        $lessons = Lesson::whereIn('material_id',$access_material_id)->pluck('id');

        $summeries = Summery::whereIn('lesson_id',$lessons)->pluck('id');
        $tests = Test::whereIn('lesson_id',$lessons)->pluck('id');
        $videos = Video::whereIn('lesson_id',$lessons)->pluck('id');
        $courses = Course::whereIn('lesson_id',$lessons)->pluck('id');
// dd($lessons);
        return $query
            ->whereIn('inquiryable_id',$summeries)
            ->orwhereIn('inquiryable_id',$tests)
            ->orwhereIn('inquiryable_id',$videos)
            ->orwhereIn('inquiryable_id',$courses);
    }
}
