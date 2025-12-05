<?php

namespace RedJasmine\ShoppingCart\Application\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\AddProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\AddProductCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\CalculateAmountCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\CalculateAmountCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\RemoveProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\RemoveProductCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\SelectProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\SelectProductCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\UpdateQuantityCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\UpdateQuantityCommandHandler;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries\FindBuyerCartQuery;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries\FindBuyerCartQueryHandler;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\ShoppingCart\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 购物车应用服务
 *
 * 负责处理购物车相关的业务逻辑，包括：
 * - 购物车商品管理
 * - 价格计算
 * - 库存校验
 * - 购物车状态管理
 *
 * @method ShoppingCart findBuyerCart(FindBuyerCartQuery $query)
 * @method ShoppingCart addProduct(AddProductCommand $command)
 * @method bool removeProduct(RemoveProductCommand $command)
 * @method bool selectProduct(SelectProductCommand $command)
 * @method ShoppingCartProduct updateQuantity(UpdateQuantityCommand $command)
 * @method OrderData calculateAmount(CalculateAmountCommand $command)
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
    ) {
    }

    public function newModel(?Data $data = null): Model
    {
        return ShoppingCart::make([
            'market' => $data?->market ?? 'default',
            'owner'  => $data?->buyer ?? null,
        ]);
    }

    public function getDefaultModelWithInfo(): array
    {
        return ['products'];
    }

    /**
     * 添加商品到购物车
     */
    public function addProductToCart(ShoppingCart $cart, ProductPurchaseFactor $command): void
    {
        // 这里应该实现添加商品的业务逻辑
        // 暂时留空，等待具体实现
    }

    /**
     * 计算购物车金额
     */
    public function calculateCartAmount(ShoppingCart $cart, PurchaseFactor $command, bool $includePromotions = true): ?OrderData
    {
        // 这里应该实现计算购物车金额的业务逻辑
        // 暂时返回null，等待具体实现
        return null;
    }

    protected static $macros = [
        'findBuyerCart' => FindBuyerCartQueryHandler::class,
        'selectProduct'   => SelectProductCommandHandler::class,
        'addProduct'      => AddProductCommandHandler::class,
        'removeProduct'   => RemoveProductCommandHandler::class,
        'updateQuantity'  => UpdateQuantityCommandHandler::class,
        'calculateAmount' => CalculateAmountCommandHandler::class,
    ];
}
