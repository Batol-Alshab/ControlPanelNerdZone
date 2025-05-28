<?php

namespace App\Filament\Widgets;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Summery;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class WidgetOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart Lesson';

    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        return Cache::remember('contentLesson',now()->addMinute(60), function(){
            $t= Test::count();
            $c= Course::count();
            $s= Summery::count();
            $v= Video::count();

            return [

                'datasets' => [
                    [
                        'label' =>'ditels Lessons',
                        'data' => [$s, $v, $t, $c,  ],
                        'backgroundColor' => ['#BA68C8','#c785d2','#d4a2dd','#e1bee7'],
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => [ 'Summery', 'Video', 'Test', 'Course'],
            ];

        });
    }

    protected function getType(): string
    {
        return 'polarArea';
    }

    protected function getOptions(): array
    {

        return [
            'scales' => [
                'r' => [
                    'pointLabels' =>[
                       'display' => true,
                       'centerPointLabels' => true,
                    ],

                ],

            ],
        ];
}

}
