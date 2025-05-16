<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource\Pages;

use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserTagCategory extends CreateRecord
{
    use ResourcePageHelper;
    protected static string $resource = UserTagCategoryResource::class;
}
