<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\Pages;

use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditTopic extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = TopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
