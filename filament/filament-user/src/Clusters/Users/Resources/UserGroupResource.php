<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentCore\Resources\Schemas\CategoryForm;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\Pages\CreateUserGroup;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\Pages\EditUserGroup;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\Pages\ListUserGroups;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\RelationManagers;
use RedJasmine\User\Application\Services\UserGroupApplicationService;
use RedJasmine\User\Domain\Data\UserGroupData;
use RedJasmine\User\Domain\Models\UserGroup;

class UserGroupResource extends Resource
{


    use ResourcePageHelper;

    public static string $service = UserGroupApplicationService::class;

    public static string $dataClass = UserGroupData::class;


    protected static ?string $model = UserGroup::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $cluster = Users::class;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user-group.labels.title');
    }

    use CategoryResource;

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index'  => ListUserGroups::route('/'),
            'create' => CreateUserGroup::route('/create'),
            'edit'   => EditUserGroup::route('/{record}/edit'),
        ];
    }
}
