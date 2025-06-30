<?php

namespace RedJasmine\ShoppingCart\UI\Http\Controllers;

use Illuminate\Http\Request;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\AddProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\RemoveProductCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\UpdateQuantityCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\SelectProductsCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands\CalculateAmountCommand;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries\FindCartQuery;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Queries\ListCartProductsQuery;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use RedJasmine\ShoppingCart\UI\Http\Requests\AddProductRequest;
use RedJasmine\ShoppingCart\UI\Http\Requests\UpdateQuantityRequest;
use RedJasmine\ShoppingCart\UI\Http\Requests\SelectProductsRequest;
use RedJasmine\ShoppingCart\UI\Http\Resources\ShoppingCartResource;
use RedJasmine\ShoppingCart\UI\Http\Resources\ShoppingCartProductResource;
use Illuminate\Http\JsonResponse;

class ShoppingCartController extends Controller
{
    public function __construct(
        protected ShoppingCartApplicationService $service
    ) {
    }

    // 获取当前用户购物车
    public function show(Request $request): JsonResponse
    {
        try {
            $query = FindCartQuery::from(['owner' => $request->user()]);
            $cart = $this->service->readRepository->findActiveByUser($query->owner);
            if (!$cart) {
                return response()->json(['data' => null]);
            }
            $cart->load('products');
            return response()->json(new ShoppingCartResource($cart));
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // 获取购物车商品列表
    public function products(Request $request): JsonResponse
    {
        try {
            $query = ListCartProductsQuery::from(['owner' => $request->user()]);
            $cart = $this->service->readRepository->findActiveByUser($query->owner);
            $products = $cart ? $cart->products : collect();
            return response()->json(ShoppingCartProductResource::collection($products));
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // 添加商品到购物车
    public function add(AddProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $identity = CartProductIdentity::from($data);
        $command = AddProductCommand::from([
            'owner' => $request->user(),
            'identity' => $identity,
            'quantity' => $data['quantity'],
            'properties' => $data['properties'] ?? [],
        ]);
        $cart = $this->service->addProduct($command);
        return response()->json(new ShoppingCartResource($cart));
    }

    // 移除商品
    public function remove(Request $request): JsonResponse
    {
        $identity = CartProductIdentity::from($request->all());
        $command = RemoveProductCommand::from([
            'owner' => $request->user(),
            'identity' => $identity,
        ]);
        $this->service->removeProduct($command);
        return response()->json(['success' => true]);
    }

    // 更新商品数量
    public function updateQuantity(UpdateQuantityRequest $request): JsonResponse
    {
        $data = $request->validated();
        $identity = CartProductIdentity::from($data);
        $command = UpdateQuantityCommand::from([
            'owner' => $request->user(),
            'identity' => $identity,
            'quantity' => $data['quantity'],
        ]);
        $cart = $this->service->updateQuantity($command);
        return response()->json(new ShoppingCartResource($cart));
    }

    // 选择/取消选择商品
    public function selectProducts(SelectProductsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $identities = array_map(fn($item) => CartProductIdentity::fromArray($item), $data['identities'] ?? []);
        $command = SelectProductsCommand::from([
            'owner' => $request->user(),
            'identities' => $identities,
            'selected' => $data['selected'],
        ]);
        $this->service->selectProducts($command);
        return response()->json(['success' => true]);
    }

    // 重新计算金额
    public function calculateAmount(Request $request): JsonResponse
    {
        $command = CalculateAmountCommand::from(['owner' => $request->user()]);
        $cart = $this->service->calculateAmount($command);
        return response()->json(new ShoppingCartResource($cart));
    }
} 