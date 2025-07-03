<?php

namespace RedJasmine\Shopping\Application\Services\ShoppingCart;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\AddProductCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\AddProductCommandHandler;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\CalculateAmountCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\CalculateAmountCommandHandler;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\RemoveProductCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\RemoveProductCommandHandler;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\SelectProductCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\SelectProductCommandHandler;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\UpdateQuantityCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\UpdateQuantityCommandHandler;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Queries\FindByMarketUserCartQuery;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Queries\FindByMarketUserCartQueryHandler;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Models\ShoppingCartProduct;
use RedJasmine\Shopping\Domain\Repositories\ShoppingCartReadRepositoryInterface;
use RedJasmine\Shopping\Domain\Repositories\ShoppingCartRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Data\Data;

/**
 * 购物车应用服务
 *
 * 负责处理购物车相关的业务逻辑，包括：
 * - 购物车商品管理
 * - 价格计算
 * - 库存校验
 * - 购物车状态管理
 *
 * @method findByMarketUser(FindByMarketUserCartQuery $query)
 * @method ShoppingCart addProduct(AddProductCommand $command)
 * @method bool removeProduct(RemoveProductCommand $command)
 * @method bool selectProduct(SelectProductCommand $command)
 * @method ShoppingCartProduct updateQuantity(UpdateQuantityCommand $command)
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
        public ShoppingCartReadRepositoryInterface $readRepository,
    ) {
    }

    public function newModel(?Data $data = null) : Model
    {
        return ShoppingCart::make([
            'market' => $data->market,
            'owner'  => $data->owner,
        ]);
    }

    public function getDefaultModelWithInfo() : array
    {
        return ['products'];
    }

    protected static $macros = [
        'findByMarketUser' => FindByMarketUserCartQueryHandler::class,


        'selectProduct'   => SelectProductCommandHandler::class,
        'addProduct'      => AddProductCommandHandler::class,
        'removeProduct'   => RemoveProductCommandHandler::class,
        'updateQuantity'  => UpdateQuantityCommandHandler::class,
        'calculateAmount' => CalculateAmountCommandHandler::class,
    ];
}