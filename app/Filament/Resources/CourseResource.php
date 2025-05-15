<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
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

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationGroup = 'Lessons';
    protected static ?string $navigationParentItem = 'Lessons';
     protected static ?int $navigationSort = 3;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('material')->required()
                    ->options(Material::all()->pluck('name','id'))
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
                    ->toggleable(),
                TextColumn::make('name'),
                TextColumn::make('lesson.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->date('Y M d'),
            ])
            ->filters([
                SelectFilter::make('lesson_id')
                    ->label('Lesson')
                    ->relationship('lesson','name')
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
            //
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
}
