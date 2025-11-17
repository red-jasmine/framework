<?php

namespace RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Pages\CreateWarehouse;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Pages\EditWarehouse;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Pages\ListWarehouses;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Schemas\WarehouseForm;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Tables\WarehouseTable;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseCreateCommand;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseDeleteCommand;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseUpdateCommand;
use RedJasmine\Warehouse\Application\Services\WarehouseApplicationService;
use RedJasmine\Warehouse\Domain\Models\Warehouse as WarehouseModel;

class WarehouseResource extends Resource
{
    use ResourcePageHelper;

    protected static ?int $navigationSort = 1;
    protected static ?string $cluster = Warehouse::class;
    protected static ?string $model = WarehouseModel::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static string $service = WarehouseApplicationService::class;
    protected static ?string $commandService = WarehouseApplicationService::class;
    protected static ?string $createCommand = WarehouseCreateCommand::class;
    protected static ?string $updateCommand = WarehouseUpdateCommand::class;
    protected static ?string $deleteCommand = WarehouseDeleteCommand::class;
    protected static bool $onlyOwner = true;

    public static function getModelLabel(): string
    {
        return __('red-jasmine-warehouse::warehouse.labels.warehouse');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('red-jasmine-warehouse::warehouse.labels.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return WarehouseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehouseTable::configure($table);
    }

    public static function callResolveRecord(WarehouseModel $model): WarehouseModel
    {
        // 加载市场/门店关联数据
        $model->load('markets');
        
        // 将 markets 关联转换为数组格式，供表单使用
        if ($model->relationLoaded('markets')) {
            $markets = $model->markets->map(function ($market) {
                return [
                    'market' => $market->market,
                    'store' => $market->store,
                    'is_active' => $market->is_active,
                    'is_primary' => $market->is_primary,
                ];
            })->toArray();
            $model->setAttribute('markets', $markets);
        }

        return $model;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'edit' => EditWarehouse::route('/{record}/edit'),
        ];
    }
}
