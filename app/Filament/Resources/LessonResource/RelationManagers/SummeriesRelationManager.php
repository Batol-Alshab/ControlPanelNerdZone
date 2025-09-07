<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SummeriesRelationManager extends RelationManager
{
    protected static string $relationship = 'Summeries';
    protected static ?string $icon = 'heroicon-o-document-text';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('messages.summery.navigation');
    }
    protected static function getRecordLabel(): ?string
    {
        return __('messages.summery.singular');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('messages.name'))
                    ->required(),

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
                // TextColumn::make('lesson.name')
                //     ->label('Lesson')
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('messages.created_at'))
                    ->sortable()
                    ->date('Y M d')
                    ->toggleable(),
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
