<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserTag extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = UserTagResource::class;
}
