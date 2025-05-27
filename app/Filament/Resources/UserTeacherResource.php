<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserTeacher;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserTeacherResource\Pages;
use App\Filament\Resources\UserTeacherResource\RelationManagers;

class UserTeacherResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'User';
    protected static ?string $label = 'Teacher';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                                    '2' => 'Teacher'
                                ])
                                ->default(2)
                                ->selectablePlaceholder(false)
                                ->dehydrated(true),

                            Select::make('permissions')
                                ->multiple()
                                ->preload()
                                ->relationship('permissions','name'),

                            // Select::make('section_id')->required()
                            //     ->label('Section')
                            //     ->relationship('section','name')

                                // ->visible(function (callable $get,callable $set) {
                                //     $roles = $get('roles');
                                //     return (is_array($roles) && in_array('3', $roles)) || $roles=='3';                        }),
                        ])->columns(2),
                    ])->columnSpanFull()
        ]);
    }


    public static function table(Table $table): Table
     {
        return $table
            ->query(User::query()->whereHas('roles',
                fn($query) => $query->where('name','teacher')))

            ->columns([
                TextColumn::make('id')
                    ->toggleable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->toggleable(),
                TextColumn::make('city')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('sex')
                        ->toggleable()
                        ->label('Gender')
                        ->getStateUsing(fn($record) => $record->sex == 0 ?  'Male': 'Female'),
                TextColumn::make('roles.name'),
                TextColumn::make('permissions.name')->sortable(),

                    // ->badge()
                    // ->color(function ($state) {
                    //     return match($state){
                    //         'admin' => 'danger',
                    //         'teacher' => 'warning',
                    //         'student' =>'success',
                    //     };
                    // }),
                // TextColumn::make('section.name')
                //     ->sortable()
            ])
            ->filters([
                // SelectFilter::make('section.name')
                //     ->label('Section')
                //     ->relationship('section','name'),
                // SelectFilter::make('roles.name')
                //     ->relationship('roles', 'name',fn ($query) => $query->where('name','!=','admin'))

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
            'index' => Pages\ListUserTeachers::route('/'),
            'create' => Pages\CreateUserTeacher::route('/create'),
            'edit' => Pages\EditUserTeacher::route('/{record}/edit'),
        ];
    }
}
