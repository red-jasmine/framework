<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogisticsCompanies extends ListRecords
{
    use ResourcePageHelper;
    protected static string $resource = LogisticsCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
