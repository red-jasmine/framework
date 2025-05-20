<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages;

use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;
}
