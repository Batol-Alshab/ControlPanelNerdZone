<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CourseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\InquiriesRelationManager;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationGroup = 'Lessons';
    protected static ?string $navigationParentItem = 'Lessons';
    protected static ?string $label = 'Training';
     protected static ?int $navigationSort = 6;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('material')->required()
                    ->options(function()
                    {
                        $user = auth()->user();
                        $roleNames = $user->getRoleNames();

                        if ($roleNames->contains('admin'))
                            return Material::pluck('name', 'id');
                        else
                        {
                            $access_material_id =$user->materials()->pluck('material_id');
                            return Material::whereIn('id',$access_material_id)->pluck('name','id');
                        }

                    })
                    ->reactive(),
                Select::make('lesson_id')->required()
                    ->label('Lesson')
                    ->relationship('lesson','name', fn ($query,callable $get)=>
                        $query->where('material_id',$get('material')))
                        ->preload()
                        ->reactive()
                        ->disabled(fn (callable $get) => !$get('material')),
                FileUpload::make('file')->required()
                    ->disk('public')->directory('SummeryFile')
                    ->maxSize(102400) // مثلاً 100MB
                    ->acceptedFileTypes(['application/pdf']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lesson.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->date('Y M d')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('lesson_id')
                    ->label('Lesson')
                    // ->relationship('lesson','name')
                    ->options(
                        function()
                        {
                            $user= auth()->user();
                            $roleNames = $user->getRoleNames();

                            if ($roleNames->contains('admin'))
                                return lesson::pluck('name', 'id');
                            else
                            {
                                $access_material_id =$user->materials()->pluck('material_id');
                                $lessons = Lesson::whereIn('material_id',$access_material_id)->pluck('name','id');
                                return $lessons;
                            }

                        }
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            InquiriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
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
        return $query->whereIn('lesson_id',$lessons);
    }
}
