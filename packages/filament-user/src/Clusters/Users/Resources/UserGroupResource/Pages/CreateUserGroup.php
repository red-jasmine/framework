<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserGroup extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = UserGroupResource::class;
}
