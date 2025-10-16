<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource;

class ListTags extends ListRecords
{
    protected static string $resource = TopicTagResource::class;

    protected function getHeaderActions() : array
    {
        return [
            CreateAction::make(),
        ];
    }
}
