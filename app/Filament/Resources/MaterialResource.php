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
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('messages.material.navigation');
    }
    public static function getNavigationLabel(): string
    {
        return __('messages.material.navigation');
    }
    public static function getLabel(): ?string
    {
        return __('messages.material.singular');
    }
    public static function getPluralLabel(): ?string
    {
        return __('messages.material.plural');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('messages.name'))
                    ->required()
                    ->maxValue(25),
                Select::make('section_id')
                    ->label(__('messages.section.label'))
                    ->required()
                    ->relationship('section','name'),
                FileUpload::make('image')
                    ->label(__('messages.image'))
                    ->nullable()
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
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('section.name')
                    ->label(__('messages.section.label'))
                    ->sortable(),
                ImageColumn::make('image')
                    ->label(__('messages.image'))
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label(__('messages.section.label'))
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
