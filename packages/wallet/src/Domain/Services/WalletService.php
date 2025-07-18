<?php

namespace RedJasmine\Wallet\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Wallet\Domain\Data\WalletConfigData;
use RedJasmine\Wallet\Domain\Data\WalletTransactionData;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Exceptions\WalletException;


class WalletService extends Service
{


    /**
     * @param  string  $type
     * @param  UserInterface  $user
     *
     * @return void
     * @throws WalletException
     */
    public function walletValidate(string $type, UserInterface $user) : void
    {
        $walletConfig = $this->getWalletConfig($type);

        if (!$walletConfig->isAllowUserType($user)) {
            throw new WalletException('不支持');
        }

    }

    /**
     * 获取钱包配置
     *
     * @param  string  $type
     *
     * @return WalletConfigData
     * @throws WalletException
     */
    public function getWalletConfig(string $type) : WalletConfigData
    {
        $configs    = config('red-jasmine-wallet.wallets', []);
        $typeConfig = $configs[$type] ?? null;
        if (blank($typeConfig)) {
            throw new WalletException('钱包类型不存在');
        }

        return WalletConfigData::from($typeConfig);

    }


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
        if (bccomp($data->amount->getAmount(), 0, 2) < 0) {
            throw new WalletException('操作金额必须大于 0');
        }

        $transaction                 = new  WalletTransaction;
        $transaction->owner          = $wallet->owner;
        $transaction->balance_before = clone $wallet->balance;
        $transaction->freeze_before  = clone $wallet->freeze;
        $transaction->wallet_id      = $wallet->id;
        $transaction->wallet_type    = $wallet->type;
        $transaction->direction      = $data->direction;
        $transaction->trade_time     = Carbon::now();
        $transaction->status         = TransactionStatusEnum::SUCCESS;


        switch ($data->direction) {
            case AmountDirectionEnum::INCOME:
                $wallet->balance     = $wallet->balance->add($data->amount);
                $transaction->amount = $data->amount;
                break;
            case AmountDirectionEnum::EXPENSE: // 支出
                // 判断是否需要验证余额
                if (($data->isAllowNegative === false) && bccomp($wallet->balance->getAmount(), $data->amount->getAmount(), 2) <= 0) {
                    throw new WalletException('余额不足');
                }
                $transaction->direction = AmountDirectionEnum::EXPENSE;
                $wallet->balance        = $wallet->balance->subtract($data->amount);
                $transaction->amount    = $data->amount->negative(); // 负数
                break;
            case AmountDirectionEnum::FROZEN: // 冻结
                if (($data->isAllowNegative === false) && bccomp($wallet->balance->getAmount(), $data->amount->getAmount(), 2) <= 0) {
                    throw new WalletException('余额不足');
                }
                $wallet->balance     = $wallet->balance->subtract($data->amount);
                $wallet->freeze      = $wallet->freeze->add($data->amount);
                $transaction->amount = $data->amount->negative(); // 负数
                break;
            case AmountDirectionEnum::UNFROZEN: // 解冻

                $wallet->balance     = $wallet->balance->add($data->amount);
                $wallet->freeze      = $wallet->freeze->subtract($data->amount);
                $transaction->amount = $data->amount; // 负数
                break;
            default:
                throw new WalletException('当前操作不支持');

        }
        // 记录余额

        $transaction->balance_after    = $wallet->balance;
        $transaction->freeze_after     = $wallet->freeze;
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
