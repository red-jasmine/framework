<?php

namespace RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource\Pages;

use RedJasmine\FilamentCard\Clusters\Cards\Resources\CardGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateCardGroup extends CreateRecord
{
    protected static string $resource = CardGroupResource::class;

    use ResourcePageHelper;
}
