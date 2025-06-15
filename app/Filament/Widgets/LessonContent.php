<?php

namespace App\Filament\Widgets;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Summery;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class LessonContent extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('messages.Chart Lessons Content');
    }

    protected function getData(): array
    {
        return Cache::remember('contentLesson_'.app()->getLocale() ,now()->addMinute(60), function(){
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
                'labels' => [ __('messages.Summery.navigation'), __('messages.Video.navigation'), __('messages.Test.navigation'), __('messages.Course.navigation')],
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
                'x' => [
                    'ticks' =>[
                        'display' =>false,
                    ]
                ],
                'y' =>[
                    'grid'=> [
                        'display'=> false //اخفاء الخطوط
                    ],
                    'ticks' => [
                        'display' => false, // إخفاء أرقام الترقيم
                    ],
                    // 'beginAtZero' => true
                ],
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
