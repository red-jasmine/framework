<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLogisticsFreightTemplate extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = LogisticsFreightTemplateResource::class;
}
