<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Relations\Relation;

class TopThreeStudentsSection1 extends BaseWidget
{
    protected static ?string $heading = null;

    protected function getTableHeading(): ?string
    {
        $section = 'الفرع العلمي'; // ممكن تغييره ديناميكياً
        return __('messages.best students') . ' ' . $section;
    }

    protected static ?int $sort = 18;

    protected function getTableQuery(): Builder|Relation|null
    {
        return User::whereHas('roles', fn($q) => $q->where('id', 3))
            ->where('section_id', 1)
            ->with('userMaterials')
            ->withSum('userMaterials', 'rate') // حساب مجموع النقاط
            ->orderByDesc('user_materials_sum_rate') // ترتيب حسب المجموع
            ->take(3); // فقط أول 3
    }

    protected function getTableColumns(): array
    {
        $columns = [
            TextColumn::make('name')->label(__('messages.name')),
            TextColumn::make('user_materials_sum_rate')
                ->label('مجموع النقاط')
                ->badge()
                ->color('primary'),
        ];

        // جلب المواد التابعة للقسم
        $materials = Section::find(1)?->materials ?? collect();

        foreach ($materials as $material) {
            // أفضل 3 قيم لكل مادة
            $topRates = User::whereHas('roles', fn($q) => $q->where('id', 3))
                ->where('section_id', 1)
                ->whereHas('userMaterials', fn($q) => $q->where('material_id', $material->id))
                ->withSum(['userMaterials' => fn($q) => $q->where('material_id', $material->id)], 'rate')
                ->orderByDesc('user_materials_sum_rate')
                ->take(3)
                ->pluck('user_materials_sum_rate')
                ->toArray();

            $topRates = array_pad($topRates, 3, null);

            $columns[] = TextColumn::make('material_' . $material->id)
                ->label($material->name)
                ->getStateUsing(function ($record) use ($material) {
                    return $record->userMaterials
                        ->where('material_id', $material->id)
                        ->sum('rate');
                })
                ->badge()
                ->color(function ($state) use ($topRates) {
                    if ($state == $topRates[0]) {
                        return 'my_green'; // الأول
                    } elseif ($state == $topRates[1]) {
                        return 'info'; // الثاني
                    } elseif ($state == $topRates[2]) {
                        return 'my_yellow'; // الثالث
                    }
                    return 'gray';
                });
        }

        return $columns;
    }
}
