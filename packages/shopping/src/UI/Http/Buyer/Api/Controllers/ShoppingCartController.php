<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\AddProductCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\CalculateAmountCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\RemoveProductCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\SelectProductCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\UpdateQuantityCommand;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Queries\FindBuyerCartQuery;
use RedJasmine\Shopping\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Requests\AddProductRequest;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Requests\SelectProductsRequest;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Resources\OrderDataResource;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Resources\ShoppingCartProductResource;
use RedJasmine\Shopping\UI\Http\Buyer\Api\Resources\ShoppingCartResource;
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
        $query = FindBuyerCartQuery::from(['buyer' => $request->user()]);

        $cart = $this->service->findBuyerCart($query);
        return new ShoppingCartResource($cart);
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
        return static::success();
    }

    // 移除商品
    public function destroy($id, Request $request) : JsonResponse
    {
        $command = RemoveProductCommand::from([
            'buyer' => $this->getOwner(),
        ]);
        $command->setKey($id);
        $this->service->removeProduct($command);
        return static::success();
    }


    // 更新商品数量
    public function updateQuantity($id, Request $request) : OrderDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());

        $command = UpdateQuantityCommand::from($request);
        $command->setKey($id);
        $cart = $this->service->updateQuantity($command);


        return $this->calculateAmount($request);
    }

    // 选择/取消选择商品
    public function selected($id, SelectProductsRequest $request) : OrderDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        $command = SelectProductCommand::from($request);
        $command->setKey($id);
        $this->service->selectProduct($command);

        return $this->calculateAmount($request);
    }

    // 重新计算金额
    public function calculateAmount(Request $request) : OrderDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        $command   = CalculateAmountCommand::from($request);
        $orderData = $this->service->calculateAmount($command);

        return new OrderDataResource($orderData);
    }
} 