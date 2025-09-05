<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages;

use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateAdmin extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = AdminResource::class;
}
