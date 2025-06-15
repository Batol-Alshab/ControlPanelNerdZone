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
    // protected static ?string $label = 'student';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationGroup(): ?string
    {
        return  __('messages.User.navigation');
    }
    public static function getNavigationLabel(): string
    {
        return __('messages.student.navigation');
    }
    public static function getLabel(): ?string
    {
        return __('messages.student.singular');
    }
    public static function getPluralLabel(): ?string
    {
        return __('messages.student.plural');
    }


    public static function form(Form $form): Form
    {
        return
        $form ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Tab::make('info')
                        ->label(__('messages.info'))
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->label(__('messages.name')),
                            TextInput::make('email')->required()
                                ->label(__('messages.email'))
                                ->email()
                                ->unique(ignoreRecord: true),
                            TextInput::make('password')
                                ->label(__('messages.password'))
                                ->required()
                                ->password()
                                ->visibleOn('create'),
                            TextInput::make('city')
                                ->label(__('messages.city'))
                                ->required(),
                            Select::make('sex')
                                ->label(__('messages.sex'))
                                ->required()
                                ->options([
                                    0 => __('messages.male') ,
                                    1 => __('messages.female'),
                                ]),
                            ])->columns(2),
                    Tab::make('Role')
                        ->label(__('messages.access'))
                        ->schema([

                            Select::make('roles')
                                ->label(__('messages.roles'))
                                ->required()
                                ->relationship('roles' , 'name')
                                ->options([
                                    '3' => Role::where('id',3)->pluck('name')->first()
                                ])
                                ->default(3)
                                ->selectablePlaceholder(false)
                                ->dehydrated(true),

                            Select::make('section_id')
                                ->required()
                                ->label(__('messages.section.label'))
                                ->relationship('section','name')
                                ->disabledOn('edit')
                                ->reactive(),
                            Fieldset::make('Access')
                                ->label(__('messages.access'))
                                ->schema([
                                    CheckboxList::make('materials')
                                        ->label(__('messages.material.label'))
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
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('section.name')
                    ->label(__('messages.section.label'))
                    ->sortable(),
                TextColumn::make('materials.name')
                    ->label(__('messages.material.label')),
                TextColumn::make('email')
                    ->label(__('messages.email'))
                    ->toggleable(),
                TextColumn::make('city')
                    ->label(__('messages.city'))
                    ->sortable(),
                TextColumn::make('sex')
                        ->label(__('messages.sex'))
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->getStateUsing(fn($record) => $record->sex == 0 ?  __('messages.male'): __('messages.female')),



            ])
            ->filters([
                SelectFilter::make('section.name')
                    ->label(__('messages.section.label'))
                    ->relationship('section','name'),
                SelectFilter::make('materials')
                    ->label(__('messages.material.label'))
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
