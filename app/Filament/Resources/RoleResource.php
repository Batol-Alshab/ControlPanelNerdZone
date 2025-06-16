<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\RelationManagers;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';


    public static function getNavigationLabel(): string
    {
        return __('messages.roles navigation');
    }
    public static function getLabel(): ?string
    {
        return __('messages.role_');
    }
    public static function getPluralLabel(): ?string
    {
        return __('messages.roles navigation');
    }

    // Role Policy
    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['admin','student']);
    }

    public static function canEdit($record): bool
    {
        // return auth()->user()?->hasRole('admin');
        return false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('messages.name')),
                // TextInput::make('guard_name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.name')),
            ])
            ->filters([
                //
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
    //             $query->where('name', '!=', 'admin');
    //         });
    // }

    // public static function getEloquentQuery(): Builder
    // {
    //     $query = parent::getEloquentQuery();
    //     $roleNames = auth()->user()->getRoleNames();

    //     if($roleNames->contains('admin'))
    //         return $query;
    //     return $query->whereRaw('0 = 1');
    // }
}
