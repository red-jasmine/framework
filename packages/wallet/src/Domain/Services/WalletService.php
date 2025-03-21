<?php

namespace RedJasmine\Wallet\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Wallet\Domain\Data\WalletTransactionData;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Exceptions\WalletException;


class WalletService
{


    /**
     *
     * @param  Wallet  $wallet
     * @param  WalletTransactionData  $data
     *
     * @return Wallet
     * @throws WalletException
     */
    public function transaction(Wallet $wallet, WalletTransactionData $data) : Wallet
    {
        // 验证钱包状态 TODO
        //  操作金额必须大于 0
        if (bccomp($data->amount->total(), 0, 2) < 0) {
            throw new WalletException('操作金额必须大于 0');
        }

        $transaction              = WalletTransaction::make();
        $transaction->wallet_id   = $wallet->id;
        $transaction->wallet_type = $wallet->type;
        $transaction->direction   = $data->direction;
        $transaction->trade_time  = Carbon::now();
        $transaction->status      = TransactionStatusEnum::SUCCESS;


        switch ($data->direction) {
            case AmountDirectionEnum::INCOME:
                $wallet->balance     = bcadd($wallet->balance, $data->amount->total(), 2);
                $transaction->amount = $data->amount->total();
                break;
            case AmountDirectionEnum::EXPENSE:
                // 判断是否需要验证余额
                if (($data->isAllowNegative === false) && bccomp($wallet->balance, $data->amount->total(), 2) <= 0) {
                    throw new WalletException('余额不足');
                }
                $transaction->direction = AmountDirectionEnum::EXPENSE;
                $wallet->balance        = bcsub($wallet->balance, $data->amount->total(), 2);
                $transaction->amount    = bcmul($data->amount->total(), -1, 2);
                break;
            case AmountDirectionEnum::FROZEN:
                if (($data->isAllowNegative === false) && bccomp($wallet->balance, $data->amount->total(), 2) <= 0) {
                    throw new WalletException('余额不足');
                }
                $wallet->balance     = bcsub($wallet->balance, $data->amount->total(), 2);
                $wallet->freeze      = bcadd($wallet->freeze, $data->amount->total(), 2);
                $transaction->amount = bcmul($data->amount->total(), -1, 2);
                break;
            case AmountDirectionEnum::UNFROZEN:
                $wallet->balance     = bcadd($wallet->balance, $data->amount->total(), 2);
                $wallet->freeze      = bcsub($wallet->freeze, $data->amount->total(), 2);
                $transaction->amount = $data->amount->total();
                break;
            default:
                throw new WalletException('当前操作不支持');

        }
        // 记录余额
        $transaction->balance          = $wallet->balance;
        $transaction->freeze           = $wallet->freeze;
        $transaction->app_id           = $data->appId;
        $transaction->transaction_type = $data->transactionType;
        $transaction->title            = $data->title;
        $transaction->description      = $data->description;
        $transaction->bill_type        = $data->billType;
        $transaction->out_trade_no     = $data->outTradeNo;
        $transaction->tags             = $data->tags;
        $transaction->remarks          = $data->remarks;

        $wallet->transaction($transaction);

        return $wallet;


    }

}
