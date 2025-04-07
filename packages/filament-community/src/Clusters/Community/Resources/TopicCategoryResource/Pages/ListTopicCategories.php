<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages;

use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTopicCategories extends ListRecords
{
    protected static string $resource = TopicCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
