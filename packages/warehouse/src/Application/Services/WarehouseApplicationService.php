<?php

namespace RedJasmine\Warehouse\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\DeleteCommandHandler;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseCreateCommand;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseCreateCommandHandler;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseDeleteCommand;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseUpdateCommand;
use RedJasmine\Warehouse\Application\Services\Commands\WarehouseUpdateCommandHandler;
use RedJasmine\Warehouse\Domain\Models\Warehouse;
use RedJasmine\Warehouse\Domain\Repositories\WarehouseRepositoryInterface;
use RedJasmine\Warehouse\Domain\Transformer\WarehouseTransformer;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * 仓库应用服务
 *
 * @method Warehouse find(FindQuery $query)
 * @method Warehouse create(WarehouseCreateCommand $command)
 * @method void update(WarehouseUpdateCommand $command)
 * @method void delete(WarehouseDeleteCommand $command)
 */
class WarehouseApplicationService extends ApplicationService
{
    /**
     * 钩子前缀
     */
    public static string $hookNamePrefix = 'warehouse.application';

    /**
     * 模型类
     */
    protected static string $modelClass = Warehouse::class;

    /**
     * 仓库
     */
    public function __construct(
        public WarehouseRepositoryInterface $repository,
        public WarehouseTransformer $transformer
    ) {
    }

    /**
     * 配置宏方法
     */
    protected static $macros = [
        'create' => WarehouseCreateCommandHandler::class,
        'update' => WarehouseUpdateCommandHandler::class,
        'delete' => DeleteCommandHandler::class,
    ];
}

