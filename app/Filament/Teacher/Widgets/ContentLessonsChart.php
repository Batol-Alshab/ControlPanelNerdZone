<?php

namespace App\Filament\Teacher\Widgets;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Summery;
use App\Models\Material;
use Illuminate\Support\Js;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ContentLessonsChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = 'Chart Lesson';

    protected function getData(): array
    {
        // return Cache::remember('countTestCourceSummeryVideoForTeacher', now()->addMinutes(60), function () {
            $user = auth()->user();
            $access_material_id =$user->materials()->pluck('material_id');
            $materials = Material::whereIn('id',$access_material_id)->get();

            $datasets = [];
            $labels = ['Test', 'Course', 'Summery', 'Video'];

            foreach($materials as $material)
            {
                $lessons = Lesson::where('material_id',$material->id)->pluck('id');
                $test = Test::whereIn('lesson_id',$lessons)->count();
                $cource = Course::whereIn('lesson_id',$lessons)->count();
                $summery = Summery::whereIn('lesson_id',$lessons)->count();
                $video = Video::whereIn('lesson_id',$lessons)->count();

                $datasets[] =
                    [
                        'label' =>$material->name,
                        'data' => [$test, $cource, $summery, $video],//,#973da7
                        'backgroundColor' => ['#6f2d7a','#ad4bbe', '#af78b8' , '#dbb0e2',],
                        'borderColor' => '#d4a0dc',
                    ];
            }

            return [
                'datasets' => $datasets,
                'labels' => $labels,
            ];
        // });
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
                duration: 2000
            },
            interaction: {
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    type: 'category'
                }
            }
        }
    JS);
    }

}
