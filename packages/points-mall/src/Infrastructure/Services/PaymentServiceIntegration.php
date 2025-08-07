<?php

namespace RedJasmine\PointsMall\Infrastructure\Services;

use Illuminate\Support\Facades\Config;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeResult;
use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\Commands\PointsExchangeOrderPaidCommand;
use RedJasmine\PointsMall\Application\Services\PointsExchangeOrder\PointsExchangeOrderApplicationService;
use RedJasmine\PointsMall\Domain\Contracts\PaymentServiceInterface;


class PaymentServiceIntegration implements PaymentServiceInterface
{

    public function __construct(
        public TradeApplicationService $tradeCommandService,

    ) {
    }

    protected function getMerchantAppId() : int
    {
        return (int) Config::get('red-jasmine-points-mall.payment.merchant_app_id');
    }

    /**
     * 创建支付单
     * @return mixed
     */
    public function create(PaymentTradeData $orderPayment) : PaymentTradeResult
    {

        $tradeCreateCommand                       = new TradeCreateCommand;
        $tradeCreateCommand->biz                  = static::BIZ;
        $tradeCreateCommand->amount               = $orderPayment->paymentAmount;
        $tradeCreateCommand->subject              = '积分订单：'.$orderPayment->merchantTradeOrderNo;
        $tradeCreateCommand->merchantTradeNo      = $orderPayment->merchantTradeNo;
        $tradeCreateCommand->merchantTradeOrderNo = $orderPayment->merchantTradeOrderNo;
        $tradeCreateCommand->description          = '';
        $tradeCreateCommand->goodDetails          = GoodDetailData::collect($orderPayment->goodDetails);
        // 配置的商户应用ID
        $tradeCreateCommand->merchantAppId  = $this->getMerchantAppId();
        $tradeCreateCommand->notifyUrl      = null;
        $tradeCreateCommand->passBackParams = null;


        $paymentTrade = $this->tradeCommandService->create($tradeCreateCommand);

        $result = $this->tradeCommandService->getSdkResult($paymentTrade);

        return PaymentTradeResult::from($result);
    }

    public function listenTradePaidEvent($event) : void
    {
        if ($event instanceof TradePaidEvent) {
            if ($event->trade && $event->trade->biz === static::BIZ) {

                // 订单支付成功
                $trade                   = $event->trade;
                $command                 = new PointsExchangeOrderPaidCommand();
                $command->orderNo        = $trade->merchant_trade_order_no;
                $command->orderPaymentId = (int) $trade->merchant_trade_no;
                $command->amount         = $trade->amount;

                $command->paymentType = 'payment';
                $command->paymentId   = $trade->trade_no;

                $command->paymentChannel   = $trade->channel_code;
                $command->paymentChannelNo = $trade->channel_trade_no;
                $command->paymentMethod    = $trade->channel_product_code;
                $command->paymentTime      = $trade->paid_time->format('Y-m-d H:i:s');


                app(PointsExchangeOrderApplicationService::class)->paid($command);


            }
        }
    }


}