<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Data\ProductInfo;
use RedJasmine\ShoppingCart\Domain\Models\Enums\ShoppingCartStatusEnum;
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
        //protected PromotionServiceInterface $promotionService
    )
    {
    }

    /**
     * @param  AddProductCommand  $command
     *
     * @return ShoppingCart
     * @throws Throwable
     */
    public function handle(AddProductCommand $command) : ShoppingCart
    {
        $this->beginDatabaseTransaction();
        try {
            // 1. 校验商品信息
            // TODO 需要验证 添加购物车后，最大的购买数量等限制
            $productInfo = $this->validateProduct($command);

            // 2. 校验库存
            $this->validateStock($command);


            // 4. 查找或创建购物车
            $cart = $this->service->repository
                        ->findActiveByUser($command->owner, $command->market)
                    ?? ShoppingCart::make([
                    'owner'  => $command->owner,
                    'market' => $command->market,
                    'status' => ShoppingCartStatusEnum::ACTIVE,
                ]);

            $cart->products;
            // 5. 构建购物车商品项
            $shoppingCartProduct = ShoppingCartProduct::make(['cart_id' => $cart->id]);
            $shoppingCartProduct->setProduct($productInfo->product);
            $shoppingCartProduct->quantity = $command->quantity;
            $shoppingCartProduct->price    = $productInfo->price;


            // 6. 添加到购物车

            $cart->addProduct($shoppingCartProduct);



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
    private function validateProduct(AddProductCommand $command) : ProductInfo
    {
        $productInfo = $this->productService->getProductInfo($command->product);

        if (!$productInfo) {
            throw new \InvalidArgumentException('商品不存在');
        }

        if (!$productInfo->isAvailable) {
            throw new \InvalidArgumentException('商品不可购买');
        }

        return $productInfo;
    }

    /**
     * 校验库存
     */
    private function validateStock(AddProductCommand $command) : void
    {
        $stockInfo = $this->stockService->getAvailableStock($command->product, $command->quantity);
        if (!$stockInfo->isAvailable) {
            throw new \InvalidArgumentException("库存不足，可用库存：{$stockInfo->stock}");
        }
    }

    /**
     * 获取价格信息
     */
    private function getPriceInfo(AddProductCommand $command) : \RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo
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