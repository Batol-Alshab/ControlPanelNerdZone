<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\Material;
use App\Models\UserMaterial;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class SumOfStudentsRatesPerMaterial extends ChartWidget
{
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 3;

    public function getHeading(): string
    {
        return __('messages.Sum Of Students Rates Per Material');
    }

    protected function getData(): array
    {
        // return Cache::remember('SumOfStudentsRatesPerMaterial_' . app()->getLocale(), now()->addMinutes(60), function () {
            $sections = Section::get();
            $color = ['#BA68C8', '#973da7','#d4a0dc'];
            $backgroundColor = [];
            $labels = [];
            $data = [];

            foreach ($sections as $section) {
                $materials = Material::where('section_id', $section->id)->get();

                foreach ($materials as $material) {
                    // مجموع النقاط لكل مادة
                    $sumRates = UserMaterial::where('material_id', $material->id)->sum('rate');

                    $labels[] = $material->name;
                    $data[] = $sumRates;

                    // اختر لون حسب القسم
                    $backgroundColor[] = $color[$section->id] ?? '#ccc';
                }
            }

            return [
                'datasets' => [
                    [
                        'label' => __('messages.student.rates_sum'),
                        'data' => $data,
                        'backgroundColor' => $backgroundColor,
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => $labels,
            ];
        // });
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
{
    return RawJs::make(<<<'JS'
    {
        animation: {
            duration: 2000
        },
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                ticks: {
                    stepSize: 10,
                },
            },
        },
        datasets: {
            bar: {
                backgroundColor: (ctx) => {
                    const chart = ctx.chart;
                    const {ctx: c, chartArea} = chart;

                    if (!chartArea) {
                        return null;
                    }

                    const gradients = [
                        ['#4facfe', '#00f2fe'],   // أزرق → سماوي
                        ['#43e97b', '#38f9d7'],  // أخضر → تركواز
                        ['#fa709a', '#fee140'],  // وردي → أصفر
                        ['#a18cd1', '#fbc2eb'],  // بنفسجي فاتح → وردي
                        ['#f6d365', '#fda085'],  // برتقالي → خوخي
                    ];

                    const index = ctx.dataIndex % gradients.length;
                    const gradient = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, gradients[index][0]);
                    gradient.addColorStop(1, gradients[index][1]);

                    return gradient;
                },
            }
        }
    }
    JS);
}
}