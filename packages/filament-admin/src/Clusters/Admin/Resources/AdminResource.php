<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources;

use RedJasmine\Admin\Application\Services\AdminApplicationService;
use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Models\Enums\AdminGenderEnum;
use RedJasmine\Admin\Domain\Models\Enums\AdminStatusEnum;
use RedJasmine\Admin\Domain\Models\Enums\AdminTypeEnum;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\RelationManagers;
use RedJasmine\FilamentAdmin\Clusters\Admin as AdminClusters;
use RedJasmine\FilamentCore\Helpers\PageHelper;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource;

class AdminResource extends UserResource
{

    public static string $service = AdminApplicationService::class;

    public static $translationNamespace = 'red-jasmine-admin::admin';

    use PageHelper;

    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $cluster = AdminClusters::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-admin::admin.labels.title');
    }


    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index'  => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit'   => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
