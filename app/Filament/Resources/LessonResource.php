<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LessonResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Filament\Resources\LessonResource\RelationManagers\TestsRelationManager;
use App\Filament\Resources\LessonResource\RelationManagers\VideosRelationManager;
use App\Filament\Resources\LessonResource\RelationManagers\CoursesRelationManager;
use App\Filament\Resources\LessonResource\RelationManagers\SummeriesRelationManager;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;
    protected static ?string $navigationGroup = 'Lessons';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('section')->required()
                    ->options(Section::all()->pluck('name','id'))
                    ->reactive(),
                Select::make('material_id')->required()
                    ->label('Material')
                    ->relationship('material', 'name', fn ($query, callable $get) =>
                        $query->where('section_id', $get('section')))
                    ->preload()
                    ->reactive()
                    ->disabled(fn (callable $get) => !$get('section')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('material.name')
                    ->label('material')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('material_id')
                    ->label('Material')
                    ->relationship('material','name')
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
            VideosRelationManager::class,
            SummeriesRelationManager::class,
            TestsRelationManager::class,
            CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query= parent::getEloquentQuery();
        $roleNames = auth()->user()->getRoleNames();
        $materials= Material::whereIn('name',$roleNames)->pluck('id');
        $lessons = Lesson::whereIn('material_id',$materials);
        return $query->whereIn('material_id',$materials);
    }
}
