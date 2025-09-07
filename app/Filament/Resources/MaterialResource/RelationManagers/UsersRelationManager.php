<?php

namespace App\Filament\Resources\MaterialResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\UserMaterial;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
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
                TextInput::make('rate')
                    ->label(__('messages.rate'))
                    ->numeric()
                    ->minValue(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->query(User::whereHas(
                'userMaterials',
                fn($query) =>
                $query->where('material_id',  static::getOwnerRecord()->id)
            )
                ->whereHas(
                    'roles',
                    fn($query) => $query->where('id', 3) //('name', 'student')
                ))
            // ->query(UserMaterial::where('material_id',$this->record->id))

            ->columns([
                TextColumn::make('id')
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('userMaterials.rate')
                    ->label(__('messages.rate'))
                    ->getStateUsing(function ($record) {
                        return $record->userMaterials()
                            ->where('material_id', static::getOwnerRecord()->id)
                            ->first()?->rate ?? '-';
                    })
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderBy(
                            UserMaterial::select('rate')
                                ->whereColumn('user_id', 'users.id')
                                ->where('material_id', static::getOwnerRecord()->id),
                            $direction
                        );
                    }),
            ])
            ->emptyStateHeading(__('messages.no_students'))

            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->action(function (array $data, $record) {
                        // تحديث قيمة الـ rate في pivot
                        $record->materials()->updateExistingPivot(
                            static::getOwnerRecord()->id,
                            ['rate' => $data['rate']]
                        );
                    })


            ])
        ;
    }
}
