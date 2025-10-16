<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListTopics extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = TopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
