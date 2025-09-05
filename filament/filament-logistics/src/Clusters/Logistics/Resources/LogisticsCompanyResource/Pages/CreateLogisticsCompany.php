<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLogisticsCompany extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = LogisticsCompanyResource::class;
}
