<?php

namespace RedJasmine\PointsMall\Domain\Services;

use Exception;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsExchangeOrderStatusEnum;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;

class PointsExchangeService
{
    public function __construct(
        protected PointsProductRepositoryInterface $productRepository,
        protected PointsExchangeOrderRepositoryInterface $orderRepository
    ) {
    }

    /**
     * 处理积分兑换
     */
    public function processExchange(
        PointsProduct $product,
        UserInterface $buyer,
        int $quantity,
        float $cashAmount = 0.0
    ) : PointsExchangeOrder {
        // 验证兑换资格
        $this->validateExchange($product, $buyer, $quantity, $cashAmount);

        // 锁定库存
        if (!$product->lockStock($quantity)) {
            throw new Exception('库存不足，无法兑换');
        }

        try {
            // 扣除积分
            if ($product->isPointsOnlyPaymentMode() || $product->isMixedPaymentMode()) {
                $this->deductPoints($buyer, $product->point * $quantity);
            }

            // 创建兑换订单
            $order = $this->createExchangeOrder($product, $buyer, $quantity, $cashAmount);

            // 减少库存
            if (!$product->decreaseStock($quantity)) {
                throw new Exception('库存扣减失败');
            }

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
     */
    public function validateExchange(
        PointsProduct $product,
        UserInterface $buyer,
        int $quantity,
        float $cashAmount = 0.0
    ) : void {
        // 检查商品状态
        if (!$product->isOnSale()) {
            throw new Exception('商品未上架，无法兑换');
        }

        // 检查库存
        if (!$product->canExchange($quantity)) {
            throw new Exception('库存不足，无法兑换');
        }

        // 检查兑换限制
        $this->validateExchangeLimit($product, $buyer, $quantity);

        // 检查积分余额
        if ($product->isPointsOnlyPaymentMode() || $product->isMixedPaymentMode()) {
            $this->validatePointsBalance($buyer, $product->point * $quantity);
        }

        // 检查现金金额
        if ($product->isMoneyOnlyPaymentMode() || $product->isMixedPaymentMode()) {
            $this->validateCashAmount($product, $cashAmount, $quantity);
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
            throw new Exception('已达到用户兑换限制');
        }

        // 检查订单数量限制
        if (!$exchangeLimit->checkOrderLimit($quantity)) {
            throw new Exception('订单数量超过限制');
        }
    }

    /**
     * 验证积分余额
     */
    private function validatePointsBalance(UserInterface $buyer, int $requiredPoints) : void
    {
        // 这里需要调用钱包服务验证积分余额
        // 暂时使用模拟验证
        $userPoints = $this->getUserPoints($buyer);

        if ($userPoints < $requiredPoints) {
            throw new Exception('积分余额不足');
        }
    }

    /**
     * 验证现金金额
     */
    private function validateCashAmount(PointsProduct $product, float $cashAmount, int $quantity) : void
    {
        $requiredAmount = $product->getActualMoneyPrice() * $quantity;

        if ($cashAmount < $requiredAmount) {
            throw new Exception('现金金额不足');
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
    private function createExchangeOrder(
        PointsProduct $product,
        UserInterface $buyer,
        int $quantity,
        float $cashAmount
    ) : PointsExchangeOrder {
        $order                   = new PointsExchangeOrder();
        $order->owner_type       = $buyer->getOwnerType();
        $order->owner_id         = $buyer->getOwnerId();
        $order->order_no         = $this->generateOrderNo();
        $order->outer_order_no   = $this->generateOuterOrderNo();
        $order->point_product_id = $product->id;
        $order->product_type     = $product->product_type;
        $order->product_id       = $product->product_id;
        $order->product_title    = $product->title;
        $order->point            = $product->point * $quantity;
        $order->price_currency   = $product->price_currency;
        $order->price_amount     = $cashAmount;
        $order->quantity         = $quantity;
        $order->payment_mode     = $product->payment_mode;
        $order->payment_status   = 'pending';
        $order->status           = PointsExchangeOrderStatusEnum::EXCHANGED;
        $order->exchange_time    = now();

        $this->orderRepository->store($order);

        return $order;
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
     * 获取用户积分余额（模拟）
     */
    private function getUserPoints(UserInterface $buyer) : int
    {
        // 这里应该调用钱包服务获取用户积分余额
        return 10000; // 模拟返回10000积分
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