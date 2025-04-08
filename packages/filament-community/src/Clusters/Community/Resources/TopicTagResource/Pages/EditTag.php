<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource;

class EditTag extends EditRecord
{
    protected static string $resource = TopicTagResource::class;

    protected function getHeaderActions() : array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
