<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lesson;
use App\Models\Summery;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use PHPUnit\Framework\returnSelf;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SummeryResource\Pages;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SummeryResource\RelationManagerseturnSelf;

class SummeryResource extends Resource
{
    protected static ?string $model = Summery::class;
    protected static ?string $navigationGroup = 'Lessons';
    protected static ?string $navigationParentItem = 'Lessons';
    protected static ?int $navigationSort = 3;


    protected static ?string $navigationIcon = 'heroicon-o-book-open';

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
                        $permisstionNames = $user->getPermissionNames();

                        if ($roleNames->contains('admin'))
                            return Material::pluck('name', 'id');
                        else
                            return Material::whereIn('name',$permisstionNames)->pluck('name','id');
                    })
                    ->reactive(),

                Select::make('lesson_id')->required()
                    ->label('Lesson')
                    ->relationship('lesson','name',fn ($query, callable $get)=>
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
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lesson.name')
                    ->label('Lesson')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->date('Y M d')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('lesson_id')
                    ->label('lesson')
                    // ->relationship('lesson','name')
                    ->options(
                        function()
                        {
                            $user= auth()->user();
                            $roleNames = $user->getRoleNames();
                            $permissionNames = $user->getPermissionNames();

                            if ($roleNames->contains('admin'))
                                return lesson::pluck('name', 'id');
                            else
                            {
                                $materials = Material::whereIn('name',$permissionNames)->pluck('id');
                                $lessons = Lesson::whereIn('material_id',$materials)->pluck('name','id');
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSummeries::route('/'),
            'create' => Pages\CreateSummery::route('/create'),
            'edit' => Pages\EditSummery::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user =auth()->user();
        $roleNames = $user->getRoleNames();
        $permissionNames = $user->getpermissionNames();

        if($roleNames->contains('admin'))
            return $query;

        $materials = Material::whereIn('name', $permissionNames)->pluck('id');
        $lessons = Lesson::whereIn('material_id',$materials)->pluck('id');
        return $query->whereIn('lesson_id',$lessons);
    }
}
