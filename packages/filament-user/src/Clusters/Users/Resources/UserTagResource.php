<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\RelationManagers;
use RedJasmine\User\Application\Services\UserTagApplicationService;
use RedJasmine\User\Domain\Data\UserTagData;
use RedJasmine\User\Domain\Enums\UserTagStatusEnum;
use RedJasmine\User\Domain\Models\UserTag;

class UserTagResource extends Resource  implements HasShieldPermissions
{
    public static function getPermissionPrefixes() : array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    use ResourcePageHelper;

    public static string $service   = UserTagApplicationService::class;
    public static string $dataClass = UserTagData::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user-tag.labels.title');
    }

    protected static ?string $model = UserTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster        = Users::class;
    protected static ?int    $navigationSort = 4;

    public static function form(Form $form) : Form
    {
        return static::categoryForm($form, static::$onlyOwner ?? false);
    }

    public static function table(Table $table) : Table
    {
        return static::categoryTable($table, static::$onlyOwner ?? false);
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
            'index'  => Pages\ListUserTags::route('/'),
            'create' => Pages\CreateUserTag::route('/create'),
            'edit'   => Pages\EditUserTag::route('/{record}/edit'),
        ];
    }
}
