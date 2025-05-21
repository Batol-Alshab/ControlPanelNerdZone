<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
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
        $form ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Tab::make('info')
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
                            ])->columns(2),
                    Tab::make('Role')
                        ->schema([
                            Select::make('roles')->required()
                                ->multiple()
                                ->relationship('roles', 'name',fn ($query) => $query->where('name','!=','admin'))
                                ->preload()
                                ->reactive(),
                            Select::make('section_id')->required()
                                ->label('Section')
                                ->relationship('section','name')
                                ->visible(function (callable $get,callable $set) {
                                    $roles = $get('roles');
                                    return (is_array($roles) && in_array('3', $roles)) || $roles=='3';                        }),
                        ])->columns(2),
                    ])->columnSpanFull()
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
                TextColumn::make('email')
                    ->toggleable(),
                TextColumn::make('city')
                    ->sortable(),
                    TextColumn::make('sex')
                        ->label('Gender')
                        ->getStateUsing(fn($record) => $record->sex == 0 ?  'Male': 'Female'),
                TextColumn::make('roles.name'),
                    // ->badge()
                    // ->color(function ($state) {
                    //     return match($state){
                    //         'admin' => 'danger',
                    //         'teacher' => 'warning',
                    //         'student' =>'success',
                    //     };
                    // }),
                TextColumn::make('section.name')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('section.name')
                    ->label('Section')
                    ->relationship('section','name'),
                SelectFilter::make('roles.name')
                    ->relationship('roles', 'name',fn ($query) => $query->where('name','!=','admin'))

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
    //ما عادت لازمة لان صار عندي سياسة
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
    //             $query->where('name', '!=', 'admin');
    //         });
    // }

}
