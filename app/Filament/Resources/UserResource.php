<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'User';
    protected static ?string $label = 'student';

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
                                ->relationship('roles' , 'name')
                                ->options([
                                    '3' => 'student'
                                ])
                                ->default(3)
                                ->selectablePlaceholder(false)
                                ->dehydrated(true),

                            Select::make('section_id')->required()
                                ->label('Section')
                                ->relationship('section','name')
                                ->disabledOn('edit')
                                ->reactive(),
                            Fieldset::make('Access')
                                ->schema([
                                    CheckboxList::make('materials')
                                        ->relationship('materials','name')
                                        ->options(fn(callable $get) => Material::where('section_id',$get('section_id'))->pluck('name','id'))
                                        ->columns(3)
                                        ->disabled(fn (callable $get) => !$get('section_id'))
                                        ->reactive(),
                                    ])

                        ])->columns(2),
                    ])->columnSpanFull()
        ]);

    }
    public static function table(Table $table): Table
    {
        return $table
            ->query(User::whereHas('roles',
                fn($query) => $query->where('name','student')))

            ->columns([
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('section.name')
                    ->sortable(),
                TextColumn::make('materials.name'),
                TextColumn::make('email')
                    ->toggleable(),
                TextColumn::make('city')
                    ->sortable(),
                TextColumn::make('sex')
                        ->label('Gender')
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->getStateUsing(fn($record) => $record->sex == 0 ?  'Male': 'Female'),



            ])
            ->filters([
                SelectFilter::make('section.name')
                    ->label('Section')
                    ->relationship('section','name'),
                SelectFilter::make('materials')
                    ->relationship('materials', 'name')//,fn ($query) => $query->where('name','!=','admin'))

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
