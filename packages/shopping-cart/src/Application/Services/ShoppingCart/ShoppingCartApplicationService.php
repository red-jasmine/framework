<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
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
use RedJasmine\Support\Data\Data;

/**
 * @method findBuyerCart(FindBuyerCartQuery $query)
 * @method ShoppingCart addProduct(AddProductCommand $command)
 * @method bool removeProduct(RemoveProductCommand $command)
 * @method bool selectProduct(SelectProductCommand $command)
 * @method ShoppingCartProduct updateQuantity(UpdateQuantityCommand $command)
 * @method OrderData calculateAmount(CalculateAmountCommand $command)
 */
class ShoppingCartApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'shopping-cart.application.shopping-cart';

    protected static string $modelClass = ShoppingCart::class;

    public function __construct(
        public ShoppingCartRepositoryInterface $repository,
    ) {
    }

    public function newModel(?Data $data = null) : Model
    {
        return ShoppingCart::make([
            'market' => $data->market ?? null,
            'owner'  => $data->buyer ?? null,
        ]);
    }

    public function getDefaultModelWithInfo() : array
    {
        return ['products'];
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


