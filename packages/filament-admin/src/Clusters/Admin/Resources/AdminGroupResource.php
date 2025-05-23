<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources;

use RedJasmine\Admin\Application\Services\AdminGroupApplicationService;
use RedJasmine\Admin\Domain\Models\AdminGroup;
use RedJasmine\FilamentAdmin\Clusters\Admin;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminGroupResource\Pages;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminGroupResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource;

class AdminGroupResource extends UserGroupResource
{


    use ResourcePageHelper;

    public static string $service = AdminGroupApplicationService::class;

    protected static ?string $model = AdminGroup::class;



    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

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
            'index'  => Pages\ListAdminGroups::route('/'),
            'create' => Pages\CreateAdminGroup::route('/create'),
            'edit'   => Pages\EditAdminGroup::route('/{record}/edit'),
        ];
    }
}
