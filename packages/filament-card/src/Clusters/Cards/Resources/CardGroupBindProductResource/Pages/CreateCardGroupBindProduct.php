<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupBindProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateCardGroupBindProduct extends CreateRecord
{
    protected static string $resource = CardGroupBindProductResource::class;
    use ResourcePageHelper;
}
