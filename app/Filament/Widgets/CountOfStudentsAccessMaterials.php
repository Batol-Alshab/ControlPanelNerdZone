<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Section;
use App\Models\Material;
use Filament\Support\RawJs;
use App\Models\UserMaterial;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class CountOfStudentsAccessMaterials extends ChartWidget
{
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return __('messages.Numer Of Students That Access Materials');
    }

    protected function getData(): array
    {
        return Cache::remember('CountOfStudentsAccessMaterials_'.app()->getLocale(), now()->addMinute(60), function () {
            $sections = Section::get();
            $color = ['#BA68C8', '#973da7', '#d4a0dc'];
            $backgroundColor=[];

            foreach ($sections as $section)
            {
                $materialAll = Material::where('section_id', $section->id)->get();
                $access = [];

                foreach ($materialAll as $material)
                {
                    $access[$material->name] = $material->users()
                        ->whereHas('roles', fn($query) => $query->where('id', 3))
                        ->count();
                    array_push($backgroundColor, $color[$section->id]);

                }

                $labels[] = array_keys($access);
                $data[] = array_values($access);

            }
            $data = array_merge($data[0], $data[1]);
            $labels = array_merge($labels[0], $labels[1]);

            // dd($backgroundColor);
            return [
                'datasets'=>[
                            [
                                'label' =>__('messages.student.navigation'),//array_keys($data['scientific']), //'scientific',
                                'data' =>$data,// array_values($data['scientific']),
                                'backgroundColor' => $backgroundColor,
                                'borderColor' => '#e1bee7',
                            ],
                        ],
                'labels' =>  $labels ,
            ];
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions():   RawJs
    {
        return RawJs::make(<<<'JS'
        {
            animation: {
                duration: 2500
            },
            interaction: {
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
            },
            scales: {
                y: {
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
        }
        JS);
    }
}
//رجعت لكل المواد سوا
//لازم لكل فرع لحال
//لازم خلي حدا من برا يستدعي هي ويدجت على كل فرع
