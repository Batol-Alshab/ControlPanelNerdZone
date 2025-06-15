<?php

namespace App\Filament\Resources\InquiryResource\Pages;

use Filament\Actions;
use App\Models\Inquiry;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\InquiryResource;
use Filament\Resources\Pages\ListRecords\Tab;

class ListInquiries extends ListRecords
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        // $filter = Inquiry::all();
        // $tabs[__('messages.all')] = Tab::make();

        // $filter = [ __('messages.No Answer'),__('messages.complete Answer'),('messages.ignorance')];
        // foreach($sections as $section)
        // {
        //     $tabs[$section->name] =Tab::make()->modifyQueryUsing(
        //         fn($query) => $query->where('section_id',$section->id)
        //     );
        // }

        return
        [
            __('messages.all') => Tab::make(),
            __('messages.complete Answer') => Tab::make()->modifyQueryUsing(
                fn($query) => $query->where('status','complete Answer')) ,
            __('messages.ignorance') => Tab::make()->modifyQueryUsing(
                fn($query) => $query->where('status','ignorance')) ,
            __('messages.No Answer') => Tab::make()->modifyQueryUsing(
                fn($query) => $query->where('status','No Answer')),
        ];

    }
}
