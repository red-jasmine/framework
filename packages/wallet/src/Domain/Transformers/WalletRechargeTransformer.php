<?php

namespace RedJasmine\Wallet\Domain\Transformers;

use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Application\Services\Recharge\Commands\CreateRechargeCommand;

class WalletRechargeTransformer implements TransformerInterface
{
    public function transform($data, $model): WalletRecharge
    {
        if ($data instanceof CreateRechargeCommand) {
            $model->wallet_id = $data->walletId;
            $model->amount = $data->amount;
            $model->currency = $data->currency;
            $model->payment_method = $data->paymentMethod;
            $model->payment_channel = $data->paymentChannel;
            $model->payment_currency = $data->paymentCurrency;
            $model->payment_amount = $data->paymentAmount;
            $model->payment_fee = $data->paymentFee;
            $model->total_payment_amount = $data->totalPaymentAmount;
            $model->extra = $data->extra;
            $model->operator_id = $data->operator->getId();
            $model->owner_id = $data->operator->getId();
        }

        return $model;
    }
} 