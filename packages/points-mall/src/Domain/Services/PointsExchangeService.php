<?php

namespace RedJasmine\PointsMall\Domain\Services;

use Exception;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderPaymentData;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductIdentity;
use RedJasmine\PointsMall\Domain\Contracts\OrderServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\ProductServiceInterface;
use RedJasmine\PointsMall\Domain\Contracts\WalletServiceInterface;
use RedJasmine\PointsMall\Domain\Data\PointsExchangeOrderData;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\PointsMall\Exceptions\PointsExchangeOrderException;
use RedJasmine\PointsMall\Exceptions\PointsProductException;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;

class PointsExchangeService extends Service
{
    public function __construct(
        protected PointsProductRepositoryInterface $productRepository,
        protected PointsExchangeOrderRepositoryInterface $orderRepository,
        protected WalletServiceInterface $walletService,
        protected ProductServiceInterface $productService,
        protected OrderServiceInterface $orderService,
        protected PaymentServiceInterface $paymentService,
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
        $product        = $purchaseFactor->pointsProduct;
        $quantity       = $purchaseFactor->quantity;

        // 验证兑换资格
        $this->validateExchange($exchangeOrderData);

        // 锁定库存
        if (!$product->lockStock($quantity)) {
            throw new Exception('库存不足，无法兑换');
        }

        try {

            // 创建兑换订单
            $exchangeOrder = $this->createExchangeOrder($exchangeOrderData);

            // 扣除积分
            if ($exchangeOrder->total_point > 0) {
                $this->walletService->deductPoints($exchangeOrder);
            }

            // 减少库存
            if (!$product->decreaseStock($quantity)) {
                throw new Exception('库存扣减失败');
            }


            // 保存商品
            $this->productRepository->update($product);

            if ($exchangeOrder->total_amount->getAmount() <= 0) {
                $exchangeOrder->paid();
            }
            return $exchangeOrder;

        } catch (Exception $e) {
            // 回滚库存锁定
            $product->unlockStock($quantity);
            throw $e;
        }
    }

    /**
     * @param  PointsExchangeOrder  $exchangeOrder
     *
     * @return PaymentTradeResult
     * @throws PointsExchangeOrderException
     */
    public function pay(PointsExchangeOrder $exchangeOrder) : PaymentTradeResult
    {
        // 创建订单领域的支付信息

        if (!$exchangeOrder->canPaymentMoney()) {
            throw new PointsExchangeOrderException('无需支付');
        }

        if (!$exchangeOrder->isPaying()) {
            throw new PointsExchangeOrderException('未在支付中');
        }


        $paymentTradeData = $this->orderService->createPayment($exchangeOrder);


        return $this->paymentService->create($paymentTradeData);

    }

    /**
     * 验证兑换资格
     * @throws PointsProductException
     */
    public function validateExchange(PointsExchangeOrderData $exchangeOrderData) : void
    {
        // 验证积分商品

        $this->validatePointsProduct($exchangeOrderData);

        // 检查兑换限制 TODO


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
        $pointProduct = $exchangeOrderData->pointsProduct;
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
        $requiredPoints = $exchangeOrderData->pointsProduct->point * $exchangeOrderData->quantity;

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
     * 创建兑换订单
     * @throws PointsProductException
     */
    private function createExchangeOrder(PointsExchangeOrderData $exchangeOrderData) : PointsExchangeOrder
    {
        $quantity = $exchangeOrderData->quantity;
        // 获取商品信息
        $productIdentity         = new ProductIdentity();
        $productIdentity->seller = $exchangeOrderData->pointsProduct->owner;
        $productIdentity->type   = $exchangeOrderData->pointsProduct->product_type;
        $productIdentity->id     = $exchangeOrderData->pointsProduct->product_id;
        $productIdentity->skuId  = $exchangeOrderData->skuId ?? $exchangeOrderData->pointsProduct->product_id;
        $productInfo             = $this->productService->getProductInfo($productIdentity);

        // 验证是否需要 地址信息
        if ($productInfo->productType->isNeedDeliveryAddress()) {
            // 如果地址那么就需要
            if ($exchangeOrderData->address === null) {
                throw new PointsProductException('请提供收货地址');
            }
        } else {
            $exchangeOrderData->address = null;
        }
        /**
         * @var PointsExchangeOrder $exchangeOrder
         */
        $exchangeOrder = PointsExchangeOrder::make([
            'owner' => $exchangeOrderData->pointsProduct->owner,
            'user'  => $exchangeOrderData->buyer,
        ]);

        $exchangeOrder->point_product_id = $exchangeOrderData->pointsProduct->id;
        $exchangeOrder->product_type     = $exchangeOrderData->pointsProduct->product_type;
        $exchangeOrder->product_id       = $exchangeOrderData->pointsProduct->product_id;

        $exchangeOrder->point        = $exchangeOrderData->pointsProduct->point;
        $exchangeOrder->price        = $exchangeOrderData->pointsProduct->price;
        $exchangeOrder->quantity     = $quantity;
        $exchangeOrder->total_point  = $exchangeOrderData->pointsProduct->point * $quantity;
        $exchangeOrder->total_amount = $exchangeOrderData->pointsProduct->price->multiply($quantity);
        $exchangeOrder->title        = $productInfo->title;
        $exchangeOrder->image        = $productInfo->image;


        $orderNo = $this->orderService->create($exchangeOrder, $productInfo);

        $exchangeOrder->sku_id         = $exchangeOrderData->skuId ?? $exchangeOrderData->pointsProduct->product_id;
        $exchangeOrder->outer_order_no = $orderNo;


        return $exchangeOrder;
    }


    public function paid(PointsExchangeOrder $exchangeOrder, OrderPaymentData $orderPaymentData) : bool
    {
        $this->orderService->paidOrderPayment($exchangeOrder, $orderPaymentData);


        $exchangeOrder->paid();

        return true;
    }
}