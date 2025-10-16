<?php

namespace RedJasmine\FilamentAdmin\Clusters\Admin\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages\ListAdmins;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages\CreateAdmin;
use RedJasmine\FilamentAdmin\Clusters\Admin\Resources\AdminResource\Pages\EditAdmin;
use Filament\Forms;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $cluster = AdminClusters::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-admin::admin.labels.title');
    }

    public static function form(Schema $schema) : Schema
    {
        $schema = parent::form($schema); 
        $schema->components([
            ...$schema->getComponents(),

            Select::make('roles')
                                   ->label(__('red-jasmine-admin::admin.fields.roles'))
                                   ->relationship('roles', 'name')
                                   ->multiple()
                                   ->preload()
                                   ->searchable()
            ,

        ]);
        return $schema;
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
            'index'  => ListAdmins::route('/'),
            'create' => CreateAdmin::route('/create'),
            'edit'   => EditAdmin::route('/{record}/edit'),
        ];
    }
}
