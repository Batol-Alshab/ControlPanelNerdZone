<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use PHPUnit\Framework\returnSelf;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MaterialResource\Pages;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Filament\Resources\MaterialResource\RelationManagers\LessonsRelationManager;
use App\Filament\Resources\MaterialResource\RelationManagers\LessonsRelationManagereturnSelf;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static ?string $navigationGroup = 'Materials';
    protected static ?int $navigationSort = 2;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()
                    // ->rules('max:25'),
                    ->maxValue(25),
                Select::make('section_id')->required()
                    ->label('section')
                    ->relationship('section','name'),
                FileUpload::make('image')->nullable()
                    ->image()
                    ->maxSize(1024)
                    ->disk('public')->directory('Material'),
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
                TextColumn::make('section.name')
                    ->label('section')
                    ->sortable(),
                ImageColumn::make('image')
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label('Section')
                    ->relationship('section','name')

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        $roleNames = $user->getRoleNames();

        if ($roleNames->contains('admin'))
            return $query;
        
        $access_material_id =$user->materials()->pluck('material_id');
        return $query->whereIn('id', $access_material_id);
    }
}
