<?php

namespace RedJasmine\Shopping\Application\Services\Commands;

use Illuminate\Support\Facades\Config;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Domain\Models\OrderProduct;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeCommandService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;

class OrderPayCommandHandler extends CommandHandler
{
    public function __construct(
        public OrderRepositoryInterface $orderRepository,
        public OrderApplicationService $orderCommandService,

        public TradeCommandService $tradeCommandService,

    ) {
    }

    public function handle(OrderPayCommand $command)
    {


        $order = $this->orderRepository->find($command->id);


        $goodDetails = GoodDetailData::collect($order->products->map(fn($orderProduct
        ) => $this->orderProductToGoodDetailData($orderProduct)
        )->toArray());


        // 订单发起支付，获取订单支付单
        $orderPayingCommand = OrderPayingCommand::from([
            'id' => $command->id,
        ]);
        // 订单发起支付
        $orderPayment = $this->orderCommandService->paying($orderPayingCommand);
        // 调用 支付领域 服务  创建支付单
        $tradeCreateCommand                       = new TradeCreateCommand;
        $tradeCreateCommand->amount               = $orderPayment->payment_amount;
        $tradeCreateCommand->subject              = filled($order->title) ? $order->title : '支付订单：'.$order->order_no;
        $tradeCreateCommand->goodDetails          = [];
        $tradeCreateCommand->merchantTradeNo      = $orderPayment->id;
        $tradeCreateCommand->merchantTradeOrderNo = $orderPayment->order_no;
        $tradeCreateCommand->description          = '';
        $tradeCreateCommand->goodDetails          = $goodDetails;
        // 配置的商户应用ID
        $tradeCreateCommand->merchantAppId  = $this->getMerchantAppId();
        $tradeCreateCommand->notifyUrl      = '';
        $tradeCreateCommand->passBackParams = null;


        $paymentTrade = $this->tradeCommandService->create($tradeCreateCommand);

        return $this->tradeCommandService->getSdkResult($paymentTrade);

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


    protected function getMerchantAppId() : int
    {
        // TODO 根据 商家ID 获取对应的支付中心的 商户以及应用ID
        return Config::get('red-jasmine-shopping.payment.merchant_app_id');
    }

}