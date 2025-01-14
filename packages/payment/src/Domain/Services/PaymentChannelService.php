<?php

namespace RedJasmine\Payment\Domain\Services;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RedJasmine\Payment\Domain\Data\ChannelRefundData;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Data\ChannelTransferData;
use RedJasmine\Payment\Domain\Exceptions\ChannelGatewayException;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Gateway\ChannelGatewayDrive;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 支付渠道服务
 * 主要调度支付渠道的、处理支付渠道返回的信息
 */
class PaymentChannelService
{


    /**
     * 创建交易单
     *
     * @param  ChannelApp  $channelApp
     * @param  ChannelProduct  $channelProduct
     * @param  Trade  $trade
     * @param  Environment  $environment
     *
     * @return ChannelTradeData
     * @throws PaymentException
     */
    public function purchase(
        ChannelApp $channelApp,
        ChannelProduct $channelProduct,
        Trade $trade,
        Environment $environment
    ) : ChannelTradeData {

        // 设置支付渠道信息
        $paymentChannelData                 = new  PaymentChannelData;
        $paymentChannelData->channelApp     = $channelApp;
        $paymentChannelData->channelProduct = $channelProduct;

        // 支付网关适配器
        $gateway = ChannelGatewayDrive::create($channelApp->channel_code);

        try {
            $channelPurchaseResult = $gateway->gateway($paymentChannelData)->purchase($trade, $environment);

            if ($channelPurchaseResult->isSuccessFul() === false) {
                throw new PaymentException($channelPurchaseResult->getMessage(), PaymentException::TRADE_PAYING);
            }


            $channelTradeData                     = new ChannelTradeData();
            $channelTradeData->tradeNo            = $trade->trade_no;
            $channelTradeData->amount             = $trade->amount;
            $channelTradeData->channelCode        = $channelApp->channel_code;
            $channelTradeData->channelProductCode = $channelProduct->code;
            $channelTradeData->channelAppId       = $channelApp->channel_app_id;
            $channelTradeData->channelMerchantId  = $channelApp->channel_merchant_id;
            $channelTradeData->channelTradeNo     = $channelPurchaseResult->getTradeNo();
            $channelTradeData->sceneCode          = $environment->scene->value;
            $channelTradeData->methodCode         = $environment->method;
            $channelTradeData->paymentTrigger     = $channelPurchaseResult->paymentTrigger;
            return $channelTradeData;
        } catch (Throwable $throwable) {

            report($throwable);
            throw new PaymentException($throwable->getMessage(), PaymentException::TRADE_PAYING);
        }


    }

    protected function getLock(Trade $trade) : Lock
    {

        $name = 'red-jasmine-payment:trade:'.$trade->id;
        return Cache::lock($name, 60);
    }


    public function completePurchase(ChannelApp $channelApp, array $data) : ChannelTradeData
    {
        // 设置支付渠道信息
        $paymentChannelData             = new  PaymentChannelData;
        $paymentChannelData->channelApp = $channelApp;
        // 支付网关适配器
        $gateway = ChannelGatewayDrive::create($channelApp->channel_code);

        return $gateway->gateway($paymentChannelData)->completePurchase($data);
    }


    /**
     * @param  ChannelApp  $channelApp
     * @param  Refund  $refund
     *
     * @return bool
     * @throws PaymentException
     */
    public function refund(ChannelApp $channelApp, Refund $refund) : bool
    {

        if (!$refund->isAllowProcessing()) {
            throw new PaymentException('不支持渠道处理退款', PaymentException::REFUND_STATUS_ERROR);
        }

        try {
            // 支付网关适配器
            $paymentChannelData             = new  PaymentChannelData;
            $paymentChannelData->channelApp = $channelApp;
            $gateway                        = ChannelGatewayDrive::create($channelApp->channel_code);
            $channelResult                  = $gateway->gateway($paymentChannelData)->refund($refund);
            if ($channelResult->isSuccessFul()) {
                $refund->processing();
                return true;
            } else {
                $refund->fail($channelResult->getMessage());
                return false;
            }
        } catch (ChannelGatewayException $channelGatewayException) {
            // 需要二次确认
            $refund->abnormal($channelGatewayException->getMessage());
            return false;
        } catch (Throwable $throwable) {
            report($throwable);
            $refund->abnormal($throwable->getMessage());
            return false;
        }


    }

    /**
     * @param  ChannelApp  $channelApp
     * @param  Refund  $refund
     *
     * @return ChannelRefundData
     * @throws PaymentException
     */
    public function refundQuery(ChannelApp $channelApp, Refund $refund) : ChannelRefundData
    {
        // 支付网关适配器
        $gateway = ChannelGatewayDrive::create($channelApp->channel_code);

        $paymentChannelData             = new  PaymentChannelData;
        $paymentChannelData->channelApp = $channelApp;

        $channelRefundQueryResult = $gateway->gateway($paymentChannelData)->refundQuery($refund);

        // 查询失败
        if ($channelRefundQueryResult->isSuccessFul() === false) {
            throw new PaymentException(
                $channelRefundQueryResult->getMessage(),
                PaymentException::CHANNEL_REFUND_QUERY_ERROR);
        }
        $channelRefundData                    = new ChannelRefundData();
        $channelRefundData->status            = $channelRefundQueryResult->status;
        $channelRefundData->refundNo          = $channelRefundQueryResult->refundNo;
        $channelRefundData->tradeNo           = $channelRefundQueryResult->tradeNo;
        $channelRefundData->refundAmount      = $channelRefundQueryResult->refundAmount;
        $channelRefundData->refundTime        = $channelRefundQueryResult->refundTime;
        $channelRefundData->channelAppId      = $channelRefundQueryResult->channelAppId;
        $channelRefundData->channelMerchantId = $channelRefundQueryResult->channelMerchantId;
        $channelRefundData->channelTradeNo    = $channelRefundQueryResult->channelTradeNo;
        $channelRefundData->channelRefundNo   = $channelRefundQueryResult->channelRefundNo;


        return $channelRefundData;

    }

    public function notifyResponse(ChannelApp $channelApp) : NotifyResponseInterface
    {

        $gateway = ChannelGatewayDrive::create($channelApp->channel_code);
        // 设置支付渠道信息
        $paymentChannelData = new  PaymentChannelData;

        $paymentChannelData->channelApp = $channelApp;

        return $gateway->gateway($paymentChannelData)->notifyResponse();

    }


    /**
     * @param  ChannelApp  $channelApp
     * @param  ChannelProduct  $channelProduct
     * @param  Transfer  $transfer
     *
     * @return bool
     * @throws PaymentException
     */
    public function transfer(
        ChannelApp $channelApp,
        ChannelProduct $channelProduct,
        Transfer $transfer
    ) : bool {

        Log::withContext([
            'transfer_no'          => $transfer->transfer_no,
            'channel_code'         => $channelApp->channel_code,
            'channel_app_id'       => $channelApp->id,
            'channel_product_code' => $channelProduct->code,
        ]);

        Log::info('payment.domain.service.channel-service.transfer:start');

        // 验证是否允许调用 TODO

        // 网络错误 调用没有返回结果失败 不确定有没有调用  那么就 异常
        // 网关调用成功, 业务异常 ？ 有明确返回结果 那就失败人工处理，  如果没有明确结果那么异常，进行二次确认
        // 网关调用成功，业务正常 ？ 业务调用成功，进行异步结果查询，进行二次确认

        try {
            $paymentChannelData                 = new  PaymentChannelData;
            $paymentChannelData->channelApp     = $channelApp;
            $paymentChannelData->channelProduct = $channelProduct;
            $gateway                            = ChannelGatewayDrive::create($channelApp->channel_code);
            $result                             = $gateway->gateway($paymentChannelData)->transfer($transfer);
            // 渠道调用失败 和 业务都正常
            $channelTransferData = new  ChannelTransferData();
            if (!$result->isSuccessFul()) {
                $channelTransferData->channelTransferNo = $result->channelTransferNo ?? null;
                $channelTransferData->status            = TransferStatusEnum::FAIL;
                $channelTransferData->message           = $result->getMessage();

                $transfer->fail($channelTransferData);
                return false;
            }
            // 调用网关成功
            $channelTransferData->channelTransferNo = $result->channelTransferNo;
            $channelTransferData->status            = TransferStatusEnum::PROCESSING;
            $transfer->processing($channelTransferData);
            return true;
        } catch (ChannelGatewayException $channelGatewayException) {
            // 渠道网关调用异常
            $transfer->abnormal($channelGatewayException->getMessage());
            return false;
        } catch (Throwable $throwable) {
            report($throwable);
            // 处理的是 没有返回结果
            $transfer->abnormal($throwable->getMessage());
            // 需要二次确认
            return false;
        }

        //  转账是否成功必须通过查询结果设置
    }


    /**
     * @param  ChannelApp  $channelApp
     * @param  ChannelProduct  $channelProduct
     * @param  Transfer  $transfer
     *
     * @return bool
     */
    public function transferQuery(
        ChannelApp $channelApp,
        ChannelProduct $channelProduct,
        Transfer $transfer
    ) : bool {

        Log::withContext([
            'transfer_no'          => $transfer->transfer_no,
            'channel_code'         => $channelApp->channel_code,
            'channel_app_id'       => $channelApp->id,
            'channel_product_code' => $channelProduct->code,
        ]);

        Log::info('payment.domain.service.channel-service.transferQuery:start');


        $channelTransferData = new  ChannelTransferData();


        try {
            Log::info('payment.domain.service.channel-service.transferQuery:ChannelGatewayDrive@create');
            $gateway                            = ChannelGatewayDrive::create($channelApp->channel_code);
            $paymentChannelData                 = new  PaymentChannelData;
            $paymentChannelData->channelApp     = $channelApp;
            $paymentChannelData->channelProduct = $channelProduct;
            Log::info('payment.domain.service.channel-service.transferQuery:gateway@transferQuery:start');
            $result = $gateway->gateway($paymentChannelData)->transferQuery($transfer);
            // 网关调用是否正常
            Log::info('payment.domain.service.channel-service.transferQuery:gateway@transferQuery:end',
                $result->toArray());


            if (!$result->isSuccessFul()) {
                // 渠道异常 则 这 查询无效
                throw new PaymentException($result->getMessage(), PaymentException::CHANNEL_REFUND_ERROR);
            }
            // 如果渠道执行成功 ，判断业务状态
            $channelTransferData->channelTransferNo = $result->channelTransferNo;
            $channelTransferData->transferTime      = $result->transferTime ?? null;
            $channelTransferData->message           = $result->getMessage();

            if ($result->status === TransferStatusEnum::SUCCESS) {
                $transfer->success($channelTransferData);
                return true;
            }
            if ($result->status === TransferStatusEnum::FAIL) {
                $transfer->fail($channelTransferData);
                return true;
            }
            if ($result->status === TransferStatusEnum::REFUND) {
                $transfer->refund($channelTransferData);
                return true;
            }

            return false;

            // 如果还是在处理中 那么需要
        } catch (ChannelGatewayException $channelGatewayException) {
            //  查询是网关遗产不处理  上报异常即可
            report($channelGatewayException);
            Log::info('payment.domain.service.channel-service.transferQuery:ChannelGatewayDrive:error:'.$channelGatewayException->getMessage());
            return false;
        } catch (Throwable $throwable) {
            report($throwable);
            // 渠道异常 则 这 查询无效
            throw $throwable;
            return false;
        }
    }


}
