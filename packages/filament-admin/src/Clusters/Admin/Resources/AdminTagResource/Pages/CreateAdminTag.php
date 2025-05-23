<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource\Pages;

use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateAdminTag extends CreateRecord
{
    use ResourcePageHelper;

    protected static string $resource = AdminTagResource::class;
}
