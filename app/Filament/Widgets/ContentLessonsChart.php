<?php

namespace App\Filament\Widgets;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Summery;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ContentLessonsChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected static ?string $heading = 'Chart Lesson';

    protected function getData(): array
    {
        return Cache::remember('statLesson', now()->addMinutes(60), function () {

            $t= Test::count();
            $c= Course::count();
            $s= Summery::count();
            $v= Video::count();

            return [

                'datasets' => [
                    [
                        'label' =>'ditels Lessons',
                        'data' => [$s, $v, $t, $c  ],
                        'backgroundColor' => ['#BA68C8','#c785d2','#d4a2dd','#e1bee7'],
                        'borderColor' => '#d4a0dc',
                    ],
                ],
                'labels' => ['Summery', 'Video','Test', 'Course'],
        ];});
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
