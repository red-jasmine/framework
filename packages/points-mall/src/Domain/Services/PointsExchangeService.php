<?php

namespace RedJasmine\PointsMall\Domain\Services;

use Exception;
use phpDocumentor\Reflection\Types\Object_;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductIdentity;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\PointsMall\Domain\Contracts\OrderServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\ProductServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\WalletServiceInterface;
use RedJasmine\PointsMall\Domain\Data\PointsExchangeOrderData;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsExchangeOrderStatusEnum;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Exceptions\PointsProductException;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;

class PointsExchangeService extends Service
{
    public function __construct(
        protected PointsProductRepositoryInterface $productRepository,
        protected PointsExchangeOrderRepositoryInterface $orderRepository,
        protected WalletServiceInterface $walletService,
        protected ProductServiceInterface $productService,
        protected OrderServiceInterface $orderService,
    ) {
    }

    /**
     * 处理积分兑换
     * @throws PointsProductException
     * @throws Exception
     */
    public function exchange(PointsExchangeOrderData $exchangeOrderData) : PointsExchangeOrder
    {
        $purchaseFactor = $exchangeOrderData;
        $product        = $purchaseFactor->pointProduct;
        $quantity       = $purchaseFactor->quantity;
        $buyer          = $purchaseFactor->buyer;
        // 验证兑换资格
        $this->validateExchange($exchangeOrderData);

        // 锁定库存
        if (!$product->lockStock($quantity)) {
            throw new Exception('库存不足，无法兑换');
        }

        try {

            // 创建兑换订单
            $order = $this->createExchangeOrder($exchangeOrderData);


            // 扣除积分
            if ($order->total_point > 0) {
                $this->deductPoints($buyer, $order->total_point);
            }

            // 减少库存
            if (!$product->decreaseStock($quantity)) {
                throw new Exception('库存扣减失败');
            }

            // 调用订单领域服务、创建 订单

            $this->createOrder();

            // 保存商品
            $this->productRepository->update($product);

            return $order;
        } catch (Exception $e) {
            // 回滚库存锁定
            $product->unlockStock($quantity);
            throw $e;
        }
    }

    /**
     * 验证兑换资格
     * @throws PointsProductException
     */
    public function validateExchange(PointsExchangeOrderData $exchangeOrderData) : void
    {

        $this->validatePointsProduct($exchangeOrderData);

        // 检查兑换限制


        // 检查积分余额

        $this->validatePointsWallet($exchangeOrderData);

    }


    /**
     * @param  PointsExchangeOrderData  $exchangeOrderData
     *
     * @return void
     * @throws PointsProductException
     */
    public function validatePointsProduct(PointsExchangeOrderData $exchangeOrderData) : void
    {
        $pointProduct = $exchangeOrderData->pointProduct;
        $quantity     = $exchangeOrderData->quantity;
        // 检查商品状态
        if (!$pointProduct->isOnSale()) {
            throw new PointsProductException('商品未上架，无法兑换');
        }

        // 检查库存
        if (!$pointProduct->canExchange($quantity)) {
            throw new PointsProductException('库存不足，无法兑换');
        }
    }


    /**
     * 验证兑换限制
     */
    private function validateExchangeLimit(PointsProduct $product, UserInterface $buyer, int $quantity) : void
    {
        $exchangeLimit = $product->getExchangeLimit();

        // 检查用户兑换次数限制
        $userExchangeCount = $this->orderRepository->countByBuyerAndProduct(
            $buyer->getOwnerType(),
            $buyer->getOwnerId(),
            $product->id
        );

        if (!$exchangeLimit->checkUserLimit($userExchangeCount)) {
            throw new PointsProductException('已达到用户兑换限制');
        }

        // 检查订单数量限制
        if (!$exchangeLimit->checkOrderLimit($quantity)) {
            throw new PointsProductException('订单数量超过限制');
        }
    }

    /**
     * 验证积分余额
     * @throws PointsProductException
     */
    private function validatePointsWallet(PointsExchangeOrderData $exchangeOrderData) : void
    {
        // 这里需要调用钱包服务验证积分余额
        // 暂时使用模拟验证

        $buyer          = $exchangeOrderData->buyer;
        $requiredPoints = $exchangeOrderData->pointProduct->point * $exchangeOrderData->quantity;

        $userPoints = $this->walletService->getPointsBalance($buyer);

        if ($userPoints < $requiredPoints) {
            throw new PointsProductException('积分余额不足');
        }
    }

    /**
     * 验证现金金额
     */
    private function validateCashAmount(PointsProduct $product, float $cashAmount, int $quantity) : void
    {
        $requiredAmount = $product->getActualMoneyPrice() * $quantity;

        if ($cashAmount < $requiredAmount) {
            throw new PointsProductException('现金金额不足');
        }
    }

    /**
     * 扣除积分
     */
    private function deductPoints(UserInterface $buyer, int $points) : void
    {
        // 这里需要调用钱包服务扣除积分
        // 暂时使用模拟扣除
        $this->deductUserPoints($buyer, $points);
    }

    /**
     * 创建兑换订单
     */
    private function createExchangeOrder(PointsExchangeOrderData $exchangeOrderData) : PointsExchangeOrder
    {
        $quantity = $exchangeOrderData->quantity;
        // 获取商品信息
        $productIdentity         = new ProductIdentity();
        $productIdentity->seller = $exchangeOrderData->pointProduct->owner;
        $productIdentity->type   = $exchangeOrderData->pointProduct->product_type;
        $productIdentity->id     = $exchangeOrderData->pointProduct->product_id;
        $productIdentity->skuId  = $exchangeOrderData->skuId;
        $productInfo             = $this->productService->getProductInfo($productIdentity);

        $exchangeOrder                   = new PointsExchangeOrder();
        $exchangeOrder->user             = $exchangeOrderData->buyer;
        $exchangeOrder->owner            = $exchangeOrderData->pointProduct->owner;
        $exchangeOrder->point_product_id = $exchangeOrderData->pointProduct->id;
        $exchangeOrder->product_type     = $exchangeOrderData->pointProduct->product_type;
        $exchangeOrder->product_id       = $exchangeOrderData->pointProduct->product_id;
        $exchangeOrder->sku_id           = $exchangeOrderData->skuId;
        $exchangeOrder->point            = $exchangeOrderData->pointProduct->point;
        $exchangeOrder->price            = $exchangeOrderData->pointProduct->price;
        $exchangeOrder->quantity         = $quantity;
        $exchangeOrder->total_point      = $exchangeOrderData->pointProduct->point * $quantity;
        $exchangeOrder->total_amount     = $exchangeOrderData->pointProduct->price->multiply($quantity);
        $exchangeOrder->title            = $productInfo->title;
        $exchangeOrder->image            = $productInfo->image;


        $this->orderRepository->store($exchangeOrder);

        $this->createOrder($exchangeOrder, $productInfo);

        return $exchangeOrder;
    }

    public function createOrder(PointsExchangeOrder $exchangeOrder, ProductInfo $productInfo)
    {

        $this->orderService->create($exchangeOrder, $productInfo);
    }

    /**
     * 生成订单号
     */
    private function generateOrderNo() : string
    {
        return 'PO'.date('YmdHis').mt_rand(1000, 9999);
    }

    /**
     * 生成关联订单号
     */
    private function generateOuterOrderNo() : string
    {
        return 'OUTER'.date('YmdHis').mt_rand(1000, 9999);
    }


    /**
     * 扣除用户积分（模拟）
     */
    private function deductUserPoints(UserInterface $buyer, int $points) : void
    {
        // 这里应该调用钱包服务扣除用户积分
        // 暂时只是模拟扣除
    }
} 