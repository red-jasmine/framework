<?php

namespace RedJasmine\Payment\Domain\Services;

use RedJasmine\Payment\Domain\Data\SettleData;
use RedJasmine\Payment\Domain\Exceptions\SettleException;
use RedJasmine\Payment\Domain\Models\Extensions\SettleDetail;
use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\SettleRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\TradeRepositoryInterface;
use Cknow\Money\Money;

/**
 * 结算服务
 */
class SettleService
{

    public function __construct(
        protected TradeRepositoryInterface $tradeRepository,
        protected SettleRepositoryInterface $settleRepository,
        protected SettleReceiverRepositoryInterface $settleReceiverRepository,
    ) {
    }


    public function create(SettleData $data) : Settle
    {
        // 验证交易信息
        $trade = $this->checkTrade($data);
        // 创建结算单
        $settle                     = Settle::make([]);
        $settle->merchant_settle_no = $data->merchantSettleNo;
        $settle->setTrade($trade);

        $amount =  new Money(0, $trade->amount->currency);
        foreach ($data->details as $detailData) {
            $detail                 = SettleDetail::make();
            $detail->receiver_type  = $detailData->receiverType;
            $detail->receiver_id    = $detailData->receiverId;
            $detail->subject        = $detailData->subject;
            $detail->description    = $detailData->description;
            $detail->amount         = $detailData->amount;
            $merchantSettleReceiver = $this->filterSettleReceiver($settle, $detail);
            $detail->setSettleReceiver($merchantSettleReceiver);
            $settle->details->add($detail);

            $amount = $amount->add($detail->amount);
        }

        $settle->amount = $amount;
        $this->settleRepository->store($settle);

        return $settle;
    }

    /**
     * @param  Settle  $settle
     * @param  SettleDetail  $settleDetail
     *
     * @return mixed
     * @throws SettleException
     */
    protected function filterSettleReceiver(Settle $settle, SettleDetail $settleDetail)
    {
        $receivers = $this->settleReceiverRepository->findByMerchantAppReceivers(
            $settle->merchant_app_id,
            $settleDetail->receiver_type,
            $settleDetail->receiver_id,
            $settle->channel_code
        );
        if ($receivers->count() <= 0) {
            throw new SettleException('结算账户未绑定');
        }
        $merchantSettleReceiver = $receivers->where('channel_merchant_id', $settle->channel_merchant_id)->first();

        if (!$merchantSettleReceiver) {
            $merchantSettleReceiver = $receivers->where('channel_merchant_id',
                SettleReceiver::ALL_CHANNEL_MERCHANT)->first();
        }
        if (!$merchantSettleReceiver) {
            throw new SettleException('结算账户未绑定当前商户账号');
        }
        return $merchantSettleReceiver;
    }

    protected function checkTrade(SettleData $data) : Trade
    {
        $trade = $this->tradeRepository->findByNo($data->tradeNo);

        // 查询最大分账比例

        // 判断总金额是否超过

        // 异常


        return $trade;
    }

}
