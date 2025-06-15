<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Section;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class UserSectionWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('messages.Chart User Section');
    }



    protected function getData(): array
    {
        return Cache::remember('userSection_'.app()->getLocale(), now()->addMinutes(60), function () {
            $user = User::all();
            $sections = Section::all();
            foreach($sections as $section)
            {
                $data[] = $user->where('section_id',$section->id)->count();
                $label[] = $section->name;
            }
            return [
                'datasets'=>[
                    [
                        'label' =>'User',
                        'data' => $data,
                        'backgroundColor' => ['#973da7','#d4a0dc'],
                        'borderColor' => '#e1bee7',
                    ],
                ],
                'labels' => $label,
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
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
                },
            },
            scales: {
                x: {
                    type: 'category',
                },
                y: {
                    grid:{
                        display:false,
                    },
                    ticks:{
                        display:false,
                    },
                },

            }
        }
    JS);
    }
}
