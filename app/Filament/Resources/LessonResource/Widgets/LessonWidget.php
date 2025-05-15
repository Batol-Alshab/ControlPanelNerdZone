<?php

namespace App\Filament\Resources\LessonResource\Widgets;

use App\Models\Test;
use App\Models\Video;
use App\Models\Course;
use App\Models\Summery;
use Filament\Widgets\ChartWidget;

class LessonWidget extends ChartWidget
{
    protected static ?string $heading = 'Lesson content';

    protected function getData(): array
    {
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
                    'borderColor' => '#d4a0dc',
                ],
            ],
            'labels' => ['Test', 'Course', 'Summery', 'Video'],
        ];

    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'display' => false,
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }
}
