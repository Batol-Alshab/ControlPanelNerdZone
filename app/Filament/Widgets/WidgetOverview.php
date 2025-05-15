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
                        'data' => [$t, $c, $s, $v, ],
                        'backgroundColor' => ['#BA68C8','#c785d2','#d4a2dd','#e1bee7'],
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => ['Test', 'Course', 'Summery', 'Video'],
            ];

        });
    }

    protected function getType(): string
    {
        return 'polarArea';
    }
}
