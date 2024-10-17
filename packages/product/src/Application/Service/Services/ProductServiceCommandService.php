<?php

namespace RedJasmine\Product\Application\Service\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceReadRepositoryInterface;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceRepositoryInterface;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagReadRepositoryInterface;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class ProductServiceCommandService extends ApplicationCommandService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.service.command';

    protected static string $modelClass = ProductService::class;


    public function __construct(
        protected ProductServiceRepositoryInterface     $repository,
        protected ProductServiceReadRepositoryInterface $readRepository
    )
    {
    }

}
