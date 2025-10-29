<?php

namespace RedJasmine\Product\Application\Brand\Services;

use RedJasmine\Product\Application\Brand\Services\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandDeleteCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandUpdateCommand;
use RedJasmine\Product\Domain\Brand\Models\ProductBrand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\Commands\CreateCommandHandler;
use RedJasmine\Support\Application\Commands\DeleteCommandHandler;
use RedJasmine\Support\Application\Commands\UpdateCommandHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * @method ProductBrand create(BrandCreateCommand $command)
 * @method void update(BrandUpdateCommand $command)
 * @method void delete(BrandDeleteCommand $command)
 */
class BrandApplicationService extends ApplicationService
{


    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.brand';
    /**
     * 定义模型
     * @var string
     */
    protected static string $modelClass = ProductBrand::class;

    /**
     * 仓库
     *
     * @param  BrandRepositoryInterface  $repository
     */
    public function __construct(
        public BrandRepositoryInterface $repository,
    ) {
    }

    /**
     * 配置宏方法
     *
     * @var array
     */
    protected static $macros = [
        'create' => CreateCommandHandler::class,
        'update' => UpdateCommandHandler::class,
        'delete' => DeleteCommandHandler::class,
    ];

    public function isAllowUse(int $id) : bool
    {
        return (bool) ($this->repository->find(FindQuery::make($id))?->isAllowUse());
    }


}
