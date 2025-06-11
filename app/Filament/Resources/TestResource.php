<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Test;
use Filament\Tables;
use App\Models\Lesson;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Ramsey\Uuid\Type\Integer;
use Filament\Resources\Resource;
use PhpParser\Node\Expr\Ternary;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\TestResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TestResource\RelationManagers;
use App\Filament\Resources\TestResource\RelationManagers\InquiriesRelationManager;
use App\Filament\Resources\TestResource\RelationManagers\QuestionsRelationManager;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;
    protected static ?string $navigationGroup = 'Lessons';
    protected static ?string $navigationParentItem = 'Lessons';
     protected static ?int $navigationSort = 5;


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
                    ->relationship('lesson','name',fn ($query, callable $get)=>
                        $query->where('material_id',$get('material')))
                    ->preload()
                    ->reactive()
                    ->disabled(fn (callable $get) => !$get('material')),

                TextInput::make('numQuestions')->required()
                    ->placeholder('اختر عدد الأسئلة بين 1 و 50')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(50),
                // Select::make('numQuestions')->required()
                //     ->options([
                //         2=>2,
                //         5=>5,
                //         10=>10,
                //         15=>15,
                //         20=>20
                //     ]),
                Toggle::make('is_complete')
                    ->disabled()
                // TextInput::make('numQuestions')->required()
                //     ->integer()
                //     ->minValue(1),
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
                    ->label('Lesson')
                    ->sortable(),
                TextColumn::make('is_complete')
                    ->formatStateUsing(fn ($state) => $state ? 'Completed' : 'Not Completed')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->sortable()
                    ->date('Y M d')
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
                TernaryFilter::make('is_complete'),

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
            QuestionsRelationManager::class,
            InquiriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user =  auth()->user();
        $roleNames = $user->getRoleNames();


        if($roleNames->contains('admin'))
            return $query;

        $access_material_id =$user->materials()->pluck('material_id');
        $lessons = Lesson::whereIn('material_id',$access_material_id)->pluck('id');
        return $query->whereIn('lesson_id',$lessons);
    }
}
