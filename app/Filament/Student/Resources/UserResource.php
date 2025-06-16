<?php

namespace App\Filament\Student\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Student\Resources\UserResource\Pages;
use App\Filament\Student\Resources\UserResource\RelationManagers;
use App\Filament\Student\Resources\UserResource\Api\Transformers\UserTransformer;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                                ->unique(ignoreRecord: true)
                                ->disabledOn('edit'),
                            TextInput::make('password')
                                ->label(__('messages.password'))
                                ->required()
                                ->password()
                                ->visibleOn('create'),
                            TextInput::make('city')
                                ->label(__('messages.city'))
                                ->required(),
                            Select::make('section_id')
                                ->required()
                                ->label(__('messages.section.label'))
                                ->relationship('section','name')
                                ->disabledOn('edit')
                                ->reactive(),
                            Select::make('sex')
                                ->label(__('messages.sex'))
                                ->required()
                                ->options([
                                    0 => __('messages.male') ,
                                    1 => __('messages.female'),
                                ]),
                            ])->columns(2),
                    // Tab::make('Role')
                    //     ->label(__('messages.access'))
                    //     ->schema([


                            // Fieldset::make('Access')
                            //     ->label(__('messages.access'))
                            //     ->schema([
                            //         CheckboxList::make('materials')
                            //             ->label(__('messages.material.label'))
                            //             ->relationship('materials','name')
                            //             ->options(fn(callable $get) => Material::where('section_id',$get('section_id'))->pluck('name','id'))
                            //             ->columns(3)
                            //             ->disabled(fn (callable $get) => !$get('section_id'))
                            //             ->reactive(),
                            //         ])

                        // ])->columns(2),
                    ])->columnSpanFull()
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('id')
                    // ->label(__('messages.id'))
                    // ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('section.name')
                    ->label(__('messages.section.label'))
                    ->sortable(),
                // TextColumn::make('materials.name')
                //     ->label(__('messages.material.label')),
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
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
            // ])
            ;
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         //
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     $query = parent::getEloquentQuery();
    //     if(auth()->user())
    //         return $query->where('id', auth()->user()->id);
    //     return $query;
    // }

    public static function getApiTransformer()
    {
        return UserTransformer::class;
    }

}
