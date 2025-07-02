<?php

namespace RedJasmine\ShoppingCart\UI\Http\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\ProductIdentity;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\AddProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\CalculateAmountCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\RemoveProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\SelectProductsCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\UpdateQuantityCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries\FindByMarketUserCartQuery;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries\ListCartProductsQuery;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\UI\Http\Api\Requests\AddProductRequest;
use RedJasmine\ShoppingCart\UI\Http\Api\Requests\SelectProductsRequest;
use RedJasmine\ShoppingCart\UI\Http\Api\Requests\UpdateQuantityRequest;
use RedJasmine\ShoppingCart\UI\Http\Api\Resources\ShoppingCartProductResource;
use RedJasmine\ShoppingCart\UI\Http\Api\Resources\ShoppingCartResource;
use Throwable;


class ShoppingCartController extends Controller
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    // 获取当前用户购物车
    public function show(Request $request) : ShoppingCartResource
    {
        try {
            $query = FindByMarketUserCartQuery::from(['owner' => $request->user()]);
            $cart  = $this->service->findByMarketUser($query);
            return new ShoppingCartResource($cart);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // 获取购物车商品列表
    public function products(Request $request) : JsonResponse
    {
        try {
            $query    = ListCartProductsQuery::from(['owner' => $request->user()]);
            $cart     = $this->service->readRepository->findActiveByUser($query->owner);
            $products = $cart ? $cart->products : collect();
            return response()->json(ShoppingCartProductResource::collection($products));
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // 添加商品到购物车
    public function add(AddProductRequest $request) : JsonResponse
    {
        $request->validated();
        $request->offsetSet('buyer', $this->getOwner());

        $command = AddProductCommand::from($request);


        $cart = $this->service->addProduct($command);
        return response()->json(new ShoppingCartResource($cart));
    }

    // 移除商品
    public function remove(Request $request) : JsonResponse
    {
        $product = ProductIdentity::from($request->all());
        $command = RemoveProductCommand::from([
            'owner'    => $request->user(),
            'identity' => $product,
        ]);
        $this->service->removeProduct($command);
        return response()->json(['success' => true]);
    }

    // 更新商品数量
    public function updateQuantity(UpdateQuantityRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $command = UpdateQuantityCommand::from($request);
        $cart    = $this->service->updateQuantity($command);
        return response()->json(new ShoppingCartResource($cart));
    }

    // 选择/取消选择商品
    public function selectProducts(SelectProductsRequest $request) : JsonResponse
    {
        $data       = $request->validated();
        $identities = array_map(fn($item) => ProductIdentity::fromArray($item), $data['identities'] ?? []);
        $command    = SelectProductsCommand::from([
            'owner'      => $request->user(),
            'identities' => $identities,
            'selected'   => $data['selected'],
        ]);
        $this->service->selectProducts($command);
        return response()->json(['success' => true]);
    }

    // 重新计算金额
    public function calculateAmount(Request $request) : JsonResponse
    {
        $request->offsetSet('buyer', $this->getOwner());
        $command = CalculateAmountCommand::from($request);



        $cart    = $this->service->calculateAmount($command);
        return response()->json(new ShoppingCartResource($cart));
    }
} 