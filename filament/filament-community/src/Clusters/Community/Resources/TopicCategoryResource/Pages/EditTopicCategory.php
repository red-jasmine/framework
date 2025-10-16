<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages;

use Filament\Actions\DeleteAction;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTopicCategory extends EditRecord
{
    protected static string $resource = TopicCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
