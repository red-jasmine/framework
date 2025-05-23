<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources;

use RedJasmine\Admin\Application\Services\AdminTagApplicationService;
use RedJasmine\Admin\Domain\Models\AdminTag;
use RedJasmine\FilamentAdmin\Clusters\Admin;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource\Pages;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminTagResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource;

class AdminTagResource extends UserTagResource
{
    use ResourcePageHelper;

    public static string     $service = AdminTagApplicationService::class;
    protected static ?string $model   = AdminTag::class;



    protected static ?string $cluster = Admin::class;


    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index'  => Pages\ListAdminTags::route('/'),
            'create' => Pages\CreateAdminTag::route('/create'),
            'edit'   => Pages\EditAdminTag::route('/{record}/edit'),
        ];
    }
}
