<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserMaterial;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $icon = 'heroicon-o-users';
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        // dd($ownerRecord->cost);
        // يظهر فقط إذا كان سعر الدرس أكبر من صفر
        return $ownerRecord->cost > 0;
    }
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return  __('messages.student.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.student.singular');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('id')
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label(__('messages.email'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),

            ])
            ->emptyStateHeading(__('messages.no_students'))

            ->filters([])
            ->headerActions([
                CreateAction::make('addUserToLesson')
                    ->model(User::class) // يختار من users
                    ->form([
                        Select::make('user_id')
                            ->label(__('messages.email'))
                            // ->options(User::where(static::getOwnerRecord()->material)
                            // ->pluck('email', 'id'))
                            ->options(function () {
                                $query = static::getOwnerRecord()->material->users();
                                // استثناء الطلاب المضافين للدرس الحالي
                                $userAlreadyAdded = static::getOwnerRecord()->users()->pluck('users.id')->toArray();

                                if (!empty($userAlreadyAdded)) {
                                    $query->whereNotIn('users.id', $userAlreadyAdded);
                                }

                                return $query->pluck('email', 'users.id');
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {
                        static::getOwnerRecord()->users()->attach($data['user_id']);
                    }),
            ])
            ->actions([
                // Tables\Actions\DeleteAction::make()
                //     ->label(__('messages.remove_purchase'))
                //     ->action(function ($records) {
                //         $owner = static::getOwnerRecord();

                //         foreach ($records as $record) {
                //             $owner->users()->detach($record->id);
                //         }
                //     })->button()->icon('heroicon-s-trash')              // أيقونة الحذف
                    // ->requiresConfirmation()  ,
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
