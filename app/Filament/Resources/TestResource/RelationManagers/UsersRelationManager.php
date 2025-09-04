<?php

namespace App\Filament\Resources\TestResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $icon = 'heroicon-o-users';
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return  __('messages.student.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.student.singular');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label(__('messages.email'))
                    // ->options(User::where(static::getOwnerRecord()->material)
                    // ->pluck('email', 'id'))
                    ->options(function () {
                        $query = static::getOwnerRecord()->lesson->material->users();
                        // استثناء الطلاب المضافين للدرس الحالي
                        $userAlreadyAdded = static::getOwnerRecord()->users()->pluck('users.id')->toArray();

                        if (!empty($userAlreadyAdded)) {
                            $query->whereNotIn('users.id', $userAlreadyAdded);
                        }

                        return $query->pluck('email', 'users.id');
                    })
                    ->hiddenOn('edit')
                    ->searchable()
                    ->required(),
                TextInput::make('passing_rate')
                    ->label(__('messages.repeated_cost'))
                    ->default(round(static::getOwnerRecord()->returned_cost / 2))
                    ->numeric()
                    ->minValue(round(static::getOwnerRecord()->returned_cost / 2))
                    ->maxValue(static::getOwnerRecord()->returned_cost),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('userTests.passing_rate')
                    ->label(__('messages.return_cost')),
                
                // TextColumn::make('userTests.rate')
                //     ->label(__('messages.rate'))
                //     ->getStateUsing(function ($record) {
                //         return $record->userMaterials()
                //             ->where('material_id', static::getOwnerRecord()->id)
                //             ->first()?->rate ?? '-';
                //     })
                //     ->sortable(query: function ($query, $direction) {
                //         return $query->orderBy(
                //             UserMaterial::select('rate')
                //                 ->whereColumn('user_id', 'users.id')
                //                 ->where('material_id', static::getOwnerRecord()->id),
                //             $direction
                //         );
                //     }),
            ])
            ->emptyStateHeading(__('messages.no_students'))
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make('addUserToLesson')
                    ->model(User::class) // يختار من users

                    ->action(function (array $data, $record) {
                        static::getOwnerRecord()->users()->attach($data['user_id'], ['passing_rate' => $data['passing_rate']]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->action(function (array $data, $record) {
                        // تحديث قيمة الـ rate في pivot
                        $record->tests()->updateExistingPivot(
                            static::getOwnerRecord()->id,
                            ['passing_rate' => $data['passing_rate']]
                        );
                    }),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $owner = static::getOwnerRecord();
                            foreach ($records as $record) {
                                $owner->users()->detach($record->id);
                            }
                        }),
                ]),
            ]);
    }
}
