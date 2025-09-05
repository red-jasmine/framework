<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource\Pages;

use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateTopic extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = TopicResource::class;
}
