<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Section;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserMaterial;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserTeacherResource\Pages;
use App\Filament\Resources\UserTeacherResource\RelationManagers;

class UserTeacherResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    public static function getNavigationGroup(): ?string
    {
        return  __('messages.User.navigation');
    }
    public static function getNavigationLabel(): string
    {
        return __('messages.teacher.navigation');
    }
    public static function getLabel(): ?string
    {
        return __('messages.teacher.singular');
    }
    public static function getPluralLabel(): ?string
    {
        return __('messages.teacher.plural');
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
                                ->label(__('messages.name'))
                                ->required(),
                            TextInput::make('email')
                                ->label(__('messages.email'))
                                ->required()->email()->unique(ignoreRecord: true),
                            TextInput::make('password')
                                ->label(__('messages.password'))
                                ->required()->password()->visibleOn('create'),
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
                                    '2' => 'Teacher'
                                ])
                                ->default(2)
                                ->selectablePlaceholder(false)
                                ->dehydrated(true),

                            Fieldset::make('Access')
                                    ->label(__('messages.access'))
                                ->schema([
                                    CheckboxList::make('materials')
                                        ->label(Section::find(1)->pluck('name')->first())
                                        // ->label('scientific')
                                        ->relationship('materials','name')
                                        ->options(fn() => Material::where('section_id',1)->pluck('name','id'))
                                        ->disableOptionWhen(function ($value,  $record)
                                        {
                                            if($record)
                                            {
                                                //طلعت كل الاستاذة  لي عندهم مواد وخليت هي المواد غير قابلة للاختيار و التعديل الا مادة هاد المستخدم
                                                $canCheck = UserMaterial::whereIn('user_id',User::whereHas('roles',
                                                    fn($query) => $query->where('id',2))->pluck('id'))
                                                    ->whereNot('user_id',$record->id)
                                                    ->pluck('material_id')->toArray();
                                            }
                                            else
                                                $canCheck = UserMaterial::whereIn('user_id',User::whereHas('roles',
                                                    fn($query) => $query->where('id',2))->pluck('id'))
                                                    ->pluck('material_id')->toArray();

                                            return in_array($value, $canCheck);
                                        })
                                        // ->disabled()
                                        ->columns(2),

                                    CheckboxList::make('materials')
                                        ->label(Section::find(2)->pluck('name')->first())
                                        ->relationship('materials','name')
                                        ->options(fn() => Material::where('section_id',2)->pluck('name','id'))
                                        ->disableOptionWhen(function ($value,  $record)
                                        {
                                            if($record)
                                            {
                                                $canCheck = UserMaterial::whereIn('user_id',User::whereHas('roles',
                                                    fn($query) => $query->where('id',2))->pluck('id'))
                                                    ->whereNot('user_id',$record->id)
                                                    ->pluck('material_id')->toArray();
                                            }
                                            else
                                                $canCheck = UserMaterial::whereIn('user_id',User::whereHas('roles',
                                                    fn($query) => $query->where('id',2))->pluck('id'))
                                                    ->pluck('material_id')->toArray();

                                            return in_array($value, $canCheck);
                                        })
                                        ->columns(2)
                                ])
                                ->columns(2),

                        ])->columns(4),

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
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('materials.name')
                    ->label(__('messages.material.label'))
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('messages.email'))
                    ->toggleable(),
                TextColumn::make('city')
                    ->label(__('messages.city'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('sex')
                    ->label(__('messages.sex'))
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->label('Gender')
                            ->label(__('messages.name'))
                        ->getStateUsing(fn($record) => $record->sex == 0 ? __('messages.male') : __('messages.female'),),

            ])
            ->filters([
                SelectFilter::make('Material')
                    ->label(__('messages.material.label'))
                    ->relationship('materials','name'),
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
