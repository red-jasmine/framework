<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart;

use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartReadRepositoryInterface;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\AddProductCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\RemoveProductCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\UpdateQuantityCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\SelectProductsCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\CalculateAmountCommandHandler;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 购物车应用服务
 * 
 * 负责处理购物车相关的业务逻辑，包括：
 * - 购物车商品管理
 * - 价格计算
 * - 库存校验
 * - 购物车状态管理
 * 
 * @method ShoppingCart addProduct(AddProductCommand $command)
 * @method bool removeProduct(RemoveProductCommand $command)
 * @method ShoppingCartProduct updateQuantity(UpdateQuantityCommand $command)
 * @method void selectProducts(SelectProductsCommand $command)
 * @method ShoppingCart calculateAmount(CalculateAmountCommand $command)
 */
class ShoppingCartApplicationService extends ApplicationService
{
    /**
     * Hook前缀配置
     * @var string
     */
    public static string $hookNamePrefix = 'shopping-cart.application.shopping-cart';

    protected static string $modelClass = ShoppingCart::class;

    public function __construct(
        public ShoppingCartRepositoryInterface $repository,
        public ShoppingCartReadRepositoryInterface $readRepository
    ) {
    }

    public function getDefaultModelWithInfo(): array
    {
        return ['products'];
    }

    protected static $macros = [
        'addProduct' => AddProductCommandHandler::class,
        'removeProduct' => RemoveProductCommandHandler::class,
        'updateQuantity' => UpdateQuantityCommandHandler::class,
        'selectProducts' => SelectProductsCommandHandler::class,
        'calculateAmount' => CalculateAmountCommandHandler::class,
    ];
} 