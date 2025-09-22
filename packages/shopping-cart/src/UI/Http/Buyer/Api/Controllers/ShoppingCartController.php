<?php

namespace RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\AddProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\CalculateAmountCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\RemoveProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\SelectProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\UpdateQuantityCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries\FindBuyerCartQuery;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Requests\AddProductRequest;
use RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Requests\SelectProductsRequest;
use RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Resources\OrderDataResource;
use RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Resources\ShoppingCartProductResource;
use RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Resources\ShoppingCartResource;
use Throwable;

class ShoppingCartController extends Controller
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    public function show(Request $request) : ShoppingCartResource
    {
        $query = FindBuyerCartQuery::from(['buyer' => $request->user()]);
        $cart = $this->service->findBuyerCart($query);
        return new ShoppingCartResource($cart);
    }

    public function products(Request $request) : JsonResponse
    {
        try {
            $cart = $this->service->repository->findActiveByUser($request->user(), $request->get('market'));
            $products = $cart ? $cart->products : collect();
            return response()->json(ShoppingCartProductResource::collection($products));
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function add(AddProductRequest $request) : JsonResponse
    {
        $request->validated();
        $request->offsetSet('buyer', $this->getOwner());
        $command = AddProductCommand::from($request);
        $this->service->addProduct($command);
        return static::success();
    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $command = RemoveProductCommand::from([
            'buyer' => $this->getOwner(),
        ]);
        $command->setKey($id);
        $this->service->removeProduct($command);
        return static::success();
    }

    public function updateQuantity($id, Request $request) : OrderDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        $command = UpdateQuantityCommand::from($request);
        $command->setKey($id);
        $this->service->updateQuantity($command);
        return $this->calculateAmount($request);
    }

    public function selected($id, SelectProductsRequest $request) : OrderDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        $command = SelectProductCommand::from($request);
        $command->setKey($id);
        $this->service->selectProduct($command);
        return $this->calculateAmount($request);
    }

    public function calculateAmount(Request $request) : OrderDataResource
    {
        $request->offsetSet('buyer', $this->getOwner());
        $command   = CalculateAmountCommand::from($request);
        $orderData = $this->service->calculateAmount($command);
        return new OrderDataResource($orderData);
    }
}


