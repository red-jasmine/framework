<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderPaymentData;
use RedJasmine\Ecommerce\Domain\Data\Payment\GoodDetailData;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Infrastructure\Services\Transformers\OrderCreateCommandTransformer;

/**
 * 订单服务集成
 *
 */
class OrderServiceIntegration implements OrderServiceInterface
{

    public function __construct(
        protected OrderApplicationService $orderApplicationService,
    ) {
    }

    public function getOrderProductSplitKey(ProductPurchaseFactor $orderProductData) : string
    {
        if (!isset($orderProductData->product->seller)) {
            return '-';
        }
        return md5(implode('|',
            [
                $orderProductData->product->seller->getType(),
                $orderProductData->product->seller->getID(),
            ]
        ));

    }


    /**
     * 创建订单
     *
     * @param  OrderData  $orderData
     *
     * @return OrderData
     */
    public function create(OrderData $orderData) : OrderData
    {
        // 转换 DTO
        $command = new OrderCreateCommandTransformer()->transform($orderData);
        // 创建订单
        // TODO 如何关联 传入的订单项目
        $order = $this->orderApplicationService->create($command);


        $orderData->setOrderNo($order->order_no);
        foreach ($order->products as $product) {
            $product->getSerialNumber();
            foreach ($orderData->products as $productData) {
                if ($productData->getSerialNumber() == $product->getSerialNumber()) {
                    $productData->setOrderProductNo($product->order_product_no);
                }
            }
        }

        return $orderData;
    }

    public function find(string $orderNo) : Order
    {
        return $this->orderApplicationService->findByNo($orderNo);
    }

    public function createOrderPayment(string $orderNo) : PaymentTradeData
    {
        $order = $this->orderApplicationService->findByNo($orderNo);


        $orderPayingCommand = new  OrderPayingCommand;
        $orderPayingCommand->setKey($orderNo);

        // 订单发起支付
        $orderPayment = $this->orderApplicationService->paying($orderPayingCommand);

        $orderPaymentData = new PaymentTradeData; //::from($orderPayment);

        $orderPaymentData->merchantTradeNo      = $orderPayment->id;
        $orderPaymentData->merchantTradeOrderNo = $orderPayment->order_no;
        $orderPaymentData->paymentAmount        = $orderPayment->payment_amount;
        $orderPaymentData->buyer                = $orderPayment->buyer;
        $orderPaymentData->seller               = $orderPayment->seller;

        // 产品信息
        $goodDetails                   = GoodDetailData::collect(
            $order->products->map(
                fn($orderProduct) => $this->orderProductToGoodDetailData($orderProduct)
            )->toArray());
        $orderPaymentData->goodDetails = $goodDetails;

        return $orderPaymentData;

    }

    protected function orderProductToGoodDetailData(OrderProduct $orderProduct) : GoodDetailData
    {
        $goodDetailData = new GoodDetailData;

        $goodDetailData->goodsId   = $orderProduct->product_id;
        $goodDetailData->goodsName = $orderProduct->title;
        $goodDetailData->price     = $orderProduct->price;
        $goodDetailData->quantity  = $orderProduct->quantity;


        return $goodDetailData;
    }

    /**
     * 支付完成支付单
     *
     * @param  string  $orderNo
     * @param  int  $orderPaymentId
     * @param  OrderPaymentData  $orderPaymentData
     *
     * @return bool
     */
    public function paidOrderPayment(string $orderNo, int $orderPaymentId, OrderPaymentData $orderPaymentData) : bool
    {
        $command = new OrderPaidCommand();


        $command->orderNo        = $orderNo;
        $command->orderPaymentId = $orderPaymentId;
        $command->amount         = $orderPaymentData->amount;

        $command->paymentType = $orderPaymentData->paymentType;
        $command->paymentId   = $orderPaymentData->paymentId;

        $command->paymentChannel   = $orderPaymentData->paymentChannel;
        $command->paymentChannelNo = $orderPaymentData->paymentChannelNo;
        $command->paymentMethod    = $orderPaymentData->paymentMethod;
        $command->paymentTime      = $orderPaymentData->paymentTime;

        return $this->orderApplicationService->paid($command);

    }


}