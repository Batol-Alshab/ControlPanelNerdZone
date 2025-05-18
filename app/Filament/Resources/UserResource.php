<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return
        $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
                TextInput::make('password')->required()->password()->visibleOn('create'),
                TextInput::make('city')->required(),
                Select::make('sex')->required()
                    ->label('Gender')
                    ->options([
                        0 => 'Male',
                        1 => 'Female',
                    ]),
                Select::make('section_id')->required()
                    ->label('Section')
                    ->relationship('section','name'),
                ])
            ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable(),
                TextColumn::make('name'),
                TextColumn::make('email')
                    ->toggleable(),
                TextColumn::make('city')
                    ->sortable(),
                    TextColumn::make('sex')
                        ->label('Gender')
                        ->getStateUsing(fn($record) => $record->sex == 0 ?  'Male': 'Female'),
                TextColumn::make('section.name')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label('Section')
                    ->relationship('section','name'),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function afterCreate(Forms\Form $form, User $record): void
    {
        $role = $form->getState()['role'];
        $record->assignRole($role);
        dd($record);
    }
    public static function afterUpdate(Forms\Form $form, User $record): void
    {
        $role = $form->getState()['role'];
        $record->assignRole($role);
        dd($record);
    }
    public static function afterSave(Forms\Form $form, User $record): void
    {
        $role = $form->getState()['role'];
        $record->syncRoles([$role]); // Use syncRoles to handle updates as well
    }
}
