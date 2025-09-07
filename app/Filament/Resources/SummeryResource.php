<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lesson;
use App\Models\Summery;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use PHPUnit\Framework\returnSelf;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\SummeryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SummeryResource\RelationManagerseturnSelf;
use App\Filament\Resources\SummeryResource\RelationManagers\InquiriesRelationManager;

class SummeryResource extends Resource
{
    protected static ?string $model = Summery::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return  __('messages.lesson.navigation');
    }
    public static function getNavigationParentItem(): ?string
    {
        return  __('messages.lesson.navigation');
    }
    public static function getNavigationLabel(): string
    {
        return __('messages.summery.navigation');
    }
    public static function getLabel(): ?string
    {
        return __('messages.summery.singular');
    }
    public static function getPluralLabel(): ?string
    {
        return __('messages.summery.plural');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('messages.name'))
                    ->required(),
                Select::make('material')
                    ->label(__('messages.material.label'))
                    ->required()
                    ->options(function () {
                        $user = Auth::user();;
                        $roleNames = $user->getRoleNames();

                        if ($roleNames->contains('admin'))
                            return Material::pluck('name', 'id');
                        else {
                            $access_material_id = $user->materials()->pluck('material_id');
                            return Material::whereIn('id', $access_material_id)->pluck('name', 'id');
                        }
                    })
                    ->reactive(),

                Select::make('lesson_id')
                    ->label(__('messages.lesson.label'))
                    ->required()
                    ->relationship('lesson', 'name', fn($query, callable $get) =>
                    $query->where('material_id', $get('material')))
                    ->preload()
                    ->reactive()
                    ->disabled(fn(callable $get) => !$get('material')),

                FileUpload::make('file')
                    ->label(__('messages.file'))
                    ->required()
                    ->disk('public')->directory('SummeryFile')
                    ->maxSize(102400) // مثلاً 100MB
                    ->acceptedFileTypes(['application/pdf']),
                Placeholder::make('')
                    ->disabled('create')
                    ->hiddenOn('create')
                    ->content(function ($get) {
                        $file = $get('file');
                        if (is_array($file) && !empty($file)) {
                            $filePath = reset($file);
                        } elseif (is_string($file)) {
                            $filePath = $file;
                        } else {
                            return __('messages.file_not_found');
                        }
                        if (Storage::disk('public')->exists($filePath)) {
                            // HTML مسموح هنا مباشرة داخل content()
                            return new HtmlString('<a href="' . asset('storage/' . $filePath) . '" target="_blank" style="color:#1D4ED8;">عرض الملف</a>');
                        }
                        return __('messages.file_not_found');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('messages.id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lesson.name')
                    ->label(__('messages.lesson.label'))
                    ->sortable(),
                TextColumn::make('file')
                    ->label(__('messages.file'))
                    ->formatStateUsing(function ($state) {
                        if ($state && Storage::disk('public')->exists($state)) {
                            // الـ badge سيكون فارغ أو يظهر أي نص تحبه داخل الـ badge
                            // بجانبه رابط "عرض الملف"
                            return '<u><a href="' . asset('storage/' . $state) . '" target="_blank" rel="noopener noreferrer" style="color: #2129c7ff;">عرض الملف</a></u>';
                        }
                        return '-';
                    })
                    ->html(),
                TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->sortable()
                    ->date('Y M d')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('lesson_id')
                    ->label(__('messages.lesson.label'))
                    // ->relationship('lesson','name')
                    ->options(
                        function () {
                            $user = Auth::user();;
                            $roleNames = $user->getRoleNames();

                            if ($roleNames->contains('admin'))
                                return lesson::pluck('name', 'id');
                            else {
                                $access_material_id = $user->materials()->pluck('material_id');
                                $lessons = Lesson::whereIn('material_id', $access_material_id)->pluck('name', 'id');
                                return $lessons;
                            }
                        }
                    ),
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
            InquiriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSummeries::route('/'),
            'create' => Pages\CreateSummery::route('/create'),
            'edit' => Pages\EditSummery::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();;
        $roleNames = $user->getRoleNames();


        if ($roleNames->contains('admin'))
            return $query;

        $access_material_id = $user->materials()->pluck('material_id');
        $lessons = Lesson::whereIn('material_id', $access_material_id)->pluck('id');
        return $query->whereIn('lesson_id', $lessons);
    }
}
