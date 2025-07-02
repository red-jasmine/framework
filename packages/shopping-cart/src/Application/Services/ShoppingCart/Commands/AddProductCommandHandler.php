<?php

namespace RedJasmine\ShoppingCart\Application\Services\ShoppingCart\Commands;

use Cknow\Money\Money;
use InvalidArgumentException;
use RedJasmine\ShoppingCart\Application\Services\ShoppingCart\ShoppingCartApplicationService;
use RedJasmine\ShoppingCart\Domain\Contracts\ProductServiceInterface;
use RedJasmine\ShoppingCart\Domain\Contracts\StockServiceInterface;
use RedJasmine\ShoppingCart\Domain\Data\ProductInfo;
use RedJasmine\ShoppingCart\Domain\Models\Enums\ShoppingCartStatusEnum;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
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

            // 1. 查找或创建购物车
            $cart = $this->service->repository
                        ->findActiveByUser($command->buyer, $command->market)
                    ?? ShoppingCart::make([
                    'owner'  => $command->buyer,
                    'market' => $command->market,
                    'status' => ShoppingCartStatusEnum::ACTIVE,
                ]);
            $cart->products;

            // 2. 构建购物车商品项
            $shoppingCartProduct = ShoppingCartProduct::make(['cart_id' => $cart->id]);
            $shoppingCartProduct->setProduct($command->product);
            $shoppingCartProduct->quantity   = $command->quantity;
            $shoppingCartProduct->customized = $command->customized;
            // 3. 添加到购物车
            $cart->addProduct($shoppingCartProduct);

            $command->quantity = $shoppingCartProduct->quantity;

            // TODO 存储一些 购买因素信息 如：渠道、导购、国家、区域、语言、货币、

            // 5. 校验商品信息
            $productInfo = $this->validateProduct($command);
            $shoppingCartProduct->setProductInfo($productInfo);
            // 6. 校验库存
            $this->validateStock($command);

            // 7. 获取价格 已最终的数量 获取价格
            $shoppingCartProduct->price = $this->getPriceInfo($command);

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
        $productInfo = $this->productService->getProductInfo($command);

        if (!$productInfo) {
            throw new InvalidArgumentException('商品不存在');
        }

        if (!$productInfo->isAvailable) {
            throw new InvalidArgumentException('商品不可购买');
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
            throw new InvalidArgumentException("库存不足，可用库存：{$stockInfo->stock}");
        }
    }

    /**
     * 获取价格信息
     */
    private function getPriceInfo(AddProductCommand $command) : Money
    {
        $price = $this->productService->getProductPrice($command);
        if (!$price) {
            throw new InvalidArgumentException('无法获取商品价格信息');
        }
        return $price;

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