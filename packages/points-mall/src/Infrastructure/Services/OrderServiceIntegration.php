<?php

namespace RedJasmine\PointsMall\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\PointsMall\Domain\Contracts\OrderServiceInterface;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Infrastructure\Services\Transformers\PointsExchangeOrderCreateCommandTransformer;
use Throwable;

/**
 * 积分商城订单服务集成
 * 对接订单领域的应用服务
 */
class OrderServiceIntegration implements OrderServiceInterface
{
    public function __construct(
        protected OrderApplicationService $orderApplicationService,
    ) {
    }

    /**
     * 获取订单商品拆分键
     *
     * @param  ProductPurchaseFactor  $orderProductData
     *
     * @return string
     */
    public function getOrderProductSplitKey(ProductPurchaseFactor $orderProductData) : string
    {
        if (!isset($orderProductData->product->seller)) {
            return '-';
        }
        return md5(implode('|', [
            $orderProductData->product->seller->getType(),
            $orderProductData->product->seller->getID(),
        ]));
    }

    public function create(PointsExchangeOrder $exchangeOrder, ProductInfo $productInfo) : string
    {

        $command = new PointsExchangeOrderCreateCommandTransformer()->transform($exchangeOrder,$productInfo);


        $order = $this->orderApplicationService->create($command);

        return $order->order_no;
    }


    /**
     * 更新订单状态
     *
     * @param  string  $orderNo
     * @param  string  $status
     * @param  array  $metadata
     *
     * @return bool
     */
    public function updateOrderStatus(string $orderNo, string $status, array $metadata = []) : bool
    {
        try {
            return $this->orderApplicationService->updateStatus($orderNo, $status, $metadata);
        } catch (Throwable $throwable) {
            return false;
        }
    }

    /**
     * 获取订单信息
     *
     * @param  string  $orderNo
     *
     * @return array|null
     */
    public function getOrderInfo(string $orderNo) : ?array
    {
        try {
            $order = $this->orderApplicationService->find($orderNo);
            return $order ? $order->toArray() : null;
        } catch (Throwable $throwable) {
            return null;
        }
    }

    /**
     * 验证订单状态
     *
     * @param  string  $orderNo
     * @param  string  $expectedStatus
     *
     * @return bool
     */
    public function validateOrderStatus(string $orderNo, string $expectedStatus) : bool
    {
        try {
            $order = $this->orderApplicationService->find($orderNo);
            return $order && $order->status === $expectedStatus;
        } catch (Throwable $throwable) {
            return false;
        }
    }

    /**
     * 创建积分兑换订单数据
     *
     * @param  array  $exchangeData
     *
     * @return array
     */
    public function createPointsExchangeOrderData(array $exchangeData) : array
    {
        return [
            'order_no' => $exchangeData['outer_order_no'],
            'buyer'    => $exchangeData['buyer'],
            'seller'   => $exchangeData['seller'],
            'products' => [
                [
                    'product_id' => $exchangeData['point_product_id'],
                    'quantity'   => $exchangeData['quantity'],
                    'price'      => $exchangeData['price_amount'],
                    'currency'   => $exchangeData['price_currency'],
                    'metadata'   => [
                        'points_exchange_order_id' => $exchangeData['id'],
                        'points'                   => $exchangeData['point'],
                        'payment_mode'             => $exchangeData['payment_mode'],
                    ]
                ]
            ],
            'metadata' => [
                'points_exchange_order_id' => $exchangeData['id'],
                'payment_mode'             => $exchangeData['payment_mode'],
                'point_amount'             => $exchangeData['point'],
            ]
        ];
    }

    /**
     * 取消订单
     *
     * @param  string  $orderNo
     * @param  string  $reason
     *
     * @return bool
     */
    public function cancelOrder(string $orderNo, string $reason = '') : bool
    {
        try {
            return $this->orderApplicationService->cancel($orderNo, $reason);
        } catch (Throwable $throwable) {
            return false;
        }
    }

    /**
     * 获取用户订单列表
     *
     * @param  string  $ownerType
     * @param  string  $ownerId
     * @param  int  $limit
     *
     * @return array
     */
    public function getUserOrders(string $ownerType, string $ownerId, int $limit = 20) : array
    {
        try {
            $orders = $this->orderApplicationService->getUserOrders($ownerType, $ownerId, $limit);
            return is_array($orders) ? $orders : ($orders ? $orders->toArray() : []);
        } catch (Throwable $throwable) {
            return [];
        }
    }


}