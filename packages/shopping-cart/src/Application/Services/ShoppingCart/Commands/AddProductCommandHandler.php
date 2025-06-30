<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\ShoppingCart\Domain\Contracts\ProductServiceInterface;
use RedJasmine\ShoppingCart\Domain\Contracts\StockServiceInterface;
use RedJasmine\ShoppingCart\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

class AddProductCommandHandler extends CommandHandler
{
    public function __construct(
        protected ShoppingCartApplicationService $service,
        protected ProductServiceInterface $productService,
        protected StockServiceInterface $stockService,
        protected PromotionServiceInterface $promotionService
    ) {
    }

    /**
     * @param AddProductCommand $command
     * @return ShoppingCart
     * @throws Throwable
     */
    public function handle(AddProductCommand $command): ShoppingCart
    {
        $this->beginDatabaseTransaction();
        try {
            // 1. 校验商品信息
            $this->validateProduct($command);

            // 2. 校验库存
            $this->validateStock($command);

            // 3. 获取价格信息
            $priceInfo = $this->getPriceInfo($command);

            // 4. 查找或创建购物车
            $cart = $this->service->repository->findActiveByUser($command->owner,$command->market) ?? new ShoppingCart([
                'owner' => $command->owner,
                'market' => $command->market
            ]);
            $cart->loadMissing('products');

            // 5. 构建购物车商品项
            $product = new ShoppingCartProduct([
                'cart_id' => $cart->id,
                'identity' => $command->identity,
                'quantity' => $command->quantity,
                'price' => $priceInfo->price,
                'original_price' => $priceInfo->originalPrice,
                'discount_amount' => $priceInfo->discountAmount,
                'selected' => true,
                'properties' => $command->properties ?: $this->productService->getSkuProperties($command->identity),
            ]);

            // 6. 添加到购物车
            $cart->addProduct($product);
            $this->service->repository->store($cart);

            $this->commitDatabaseTransaction();
        } catch (Throwable $e) {
            $this->rollBackDatabaseTransaction();
            throw $e;
        }
        return $cart;
    }

    /**
     * 校验商品信息
     */
    private function validateProduct(AddProductCommand $command): void
    {
        $productInfo = $this->productService->getProductInfo($command->identity);
        if (!$productInfo) {
            throw new \InvalidArgumentException('商品不存在');
        }

        if (!$this->productService->isProductAvailable($command->identity)) {
            throw new \InvalidArgumentException('商品不可购买');
        }
    }

    /**
     * 校验库存
     */
    private function validateStock(AddProductCommand $command): void
    {
        if (!$this->stockService->checkStock($command->identity, $command->quantity)) {
            $availableStock = $this->stockService->getAvailableStock($command->identity);
            throw new \InvalidArgumentException("库存不足，可用库存：{$availableStock}");
        }
    }

    /**
     * 获取价格信息
     */
    private function getPriceInfo(AddProductCommand $command): \RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo
    {
        $priceInfo = $this->productService->getProductPrice($command->identity);
        if (!$priceInfo) {
            throw new \InvalidArgumentException('无法获取商品价格信息');
        }

        // 应用营销优惠
        $promotionInfo = $this->promotionService->getProductPromotion(
            $command->identity,
            $priceInfo->originalPrice->getAmount() / 100 // 转换为元
        );

        if ($promotionInfo) {
            $priceInfo = $promotionInfo;
        }

        return $priceInfo;
    }
} 