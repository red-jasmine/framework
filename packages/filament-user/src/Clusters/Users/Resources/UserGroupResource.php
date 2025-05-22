<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\RelationManagers;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use RedJasmine\User\Application\Services\UserGroupApplicationService;
use RedJasmine\User\Domain\Data\UserGroupData;
use RedJasmine\User\Domain\Models\UserGroup;

class UserGroupResource extends Resource
{


    use ResourcePageHelper;

    public static string $service = UserGroupApplicationService::class;

    public static string $dataClass = UserGroupData::class;


    protected static ?string $model = UserGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $cluster = Users::class;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user-group.labels.title');
    }

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
            'index'  => Pages\ListUserGroups::route('/'),
            'create' => Pages\CreateUserGroup::route('/create'),
            'edit'   => Pages\EditUserGroup::route('/{record}/edit'),
        ];
    }
}
