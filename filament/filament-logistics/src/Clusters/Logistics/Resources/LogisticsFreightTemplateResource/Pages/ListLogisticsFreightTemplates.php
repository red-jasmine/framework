<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\Pages;

use Filament\Actions\CreateAction;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogisticsFreightTemplates extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = LogisticsFreightTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
