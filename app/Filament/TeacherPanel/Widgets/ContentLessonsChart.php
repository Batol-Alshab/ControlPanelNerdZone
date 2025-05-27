<?php

namespace App\Filament\TeacherPanel\Widgets;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Summery;
use App\Models\Material;
use Illuminate\Support\Js;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ContentLessonsChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected static ?string $heading = 'Chart Lesson';

    protected function getData(): array
    {
        // return Cache::remember('countTestCourceSummeryVideoForTeacher', now()->addMinutes(60), function () {
            $user = auth()->user();
            $permissionNames = $user->getPermissionNames();
            $materials = Material::whereIn('name',$permissionNames)->get();//->pluck('id','name');

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
                        'data' => [$test, $cource, $summery, $video],
                        'backgroundColor' => ['#BA68C8','#c785d2','#d4a2dd','#e1bee7'],
                        'borderColor' => '#d4a0dc',
                        'borderRadius' => 10,
                    ];
            }

            return [
                'datasets' => $datasets,
                'labels' => $labels,
        ];}

    protected function getType(): string
    {
        return 'bar';
    }


 

//     protected function getOptions(): array
//     {

//         return [
//             'responsive' =>  true,
//             'plugins' => [
//                 'legend' => [
//                     'position' => 'top',
//                     ]
//                 ],
//             'title'=> [
//                 'display' => true,
//                 'text'=> 'Chart.js Bar Chart'
//             ],
//             'scales' => [
//                 'y' => [
//                     'ticks' => [
//                         'stepSize' => 1,
//                     ],
//                 ],
//             ],
//         ];
// }

}
