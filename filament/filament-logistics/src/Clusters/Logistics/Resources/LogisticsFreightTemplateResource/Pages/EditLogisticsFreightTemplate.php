<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\Pages;

use Filament\Actions\DeleteAction;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogisticsFreightTemplate extends EditRecord
{
    use ResourcePageHelper;
    protected static string $resource = LogisticsFreightTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
