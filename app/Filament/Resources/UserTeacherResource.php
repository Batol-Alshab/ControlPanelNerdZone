<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserMaterial;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
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

                            Fieldset::make('Access')
                                ->schema([
                                    CheckboxList::make('materials')
                                        ->label('scientific')
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
                                        ->label('literary')
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
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('materials.name')->sortable(),
                TextColumn::make('email')
                    ->toggleable(),
                TextColumn::make('city')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('sex')
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->label('Gender')
                        ->getStateUsing(fn($record) => $record->sex == 0 ?  'Male': 'Female'),

            ])
            ->filters([
                SelectFilter::make('Material')
                    // ->label('Material')
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
