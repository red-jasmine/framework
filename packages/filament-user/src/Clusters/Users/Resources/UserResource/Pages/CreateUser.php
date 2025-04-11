<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = UserResource::class;
}
