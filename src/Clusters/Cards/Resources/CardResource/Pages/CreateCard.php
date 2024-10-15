<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateCard extends CreateRecord
{
    protected static string $resource = CardResource::class;
    use ResourcePageHelper;
}
