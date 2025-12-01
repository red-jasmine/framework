<?php

namespace RedJasmine\Product\Application\Service\Services;

use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceRepositoryInterface;
use RedJasmine\Product\Domain\Service\Transformer\ProductServiceTransformer;
use RedJasmine\Support\Application\ApplicationService;

class ProductServiceApplicationService extends ApplicationService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.service';

    protected static string $modelClass = ProductService::class;


    public function __construct(
        public ProductServiceRepositoryInterface $repository,
        public ProductServiceTransformer $transformer,
    ) {
    }

}
