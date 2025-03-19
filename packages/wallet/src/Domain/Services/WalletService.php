<?php

namespace RedJasmine\Wallet\Domain\Services;

use Illuminate\Support\Facades\DB;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Wallet\DataTransferObjects\WalletActionDTO;
use RedJasmine\Wallet\Domain\Data\WalletTransactionData;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Enums\WalletStatusEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Exceptions\WalletException;
use Throwable;


class WalletService
{


    // 收入：
    // 支出：
    // 冻结:
    // 解冻:

    /**
     * @throws WalletException
     */
    public function transaction(Wallet $wallet, WalletTransactionData $walletTransactionData) : Wallet
    {
        // 验证钱包状态 TODO
        //  操作金额必须大于 0
        if (bccomp($walletTransactionData->amount->total(), 0, 2) < 0) {
            throw new WalletException('操作金额必须大于 0');
        }

        $transaction                   = WalletTransaction::make();
        $transaction->wallet_id        = $wallet->id;
        $transaction->amount           = $walletTransactionData->amount;
        $transaction->transaction_type = $walletTransactionData->transactionType;
        $transaction->status           = TransactionStatusEnum::SUCCESS;
        $transaction->title            = $walletTransactionData->title;
        $transaction->description      = $walletTransactionData->description;
        $transaction->bill_type        = $walletTransactionData->billType;
        $transaction->order_no         = $walletTransactionData->orderNo;
        $transaction->tags             = $walletTransactionData->tags;
        $transaction->remarks          = $walletTransactionData->remarks;


        switch ($walletTransactionData->transactionType) {
            case TransactionTypeEnum::REFUND:
            case TransactionTypeEnum::RECHARGE:
                $transaction->direction = AmountDirectionEnum::INCOME;
                $wallet->balance        = bcadd($wallet->balance, $walletTransactionData->amount->total(), 2);

                break;
            case TransactionTypeEnum::PAYMENT:
            case TransactionTypeEnum::WITHDRAWAL:
                $transaction->direction = AmountDirectionEnum::EXPENSE;
                $wallet->balance        = bcsub($wallet->balance, $walletTransactionData->amount->total(), 2);
                break;
            case TransactionTypeEnum::FROZEN:
                $transaction->direction = AmountDirectionEnum::OTHER;
                $wallet->balance        = bcsub($wallet->balance, $walletTransactionData->amount->total(), 2);
                $wallet->freeze         = bcadd($wallet->freeze, $walletTransactionData->amount->total(), 2);

                break;
            case TransactionTypeEnum::UNFROZEN:
                $transaction->direction = AmountDirectionEnum::OTHER;
                $wallet->balance        = bcadd($wallet->balance, $walletTransactionData->amount->total(), 2);
                $wallet->freeze         = bcsub($wallet->freeze, $walletTransactionData->amount->total(), 2);
                break;
            case TransactionTypeEnum::TRANSFER:
                // 如果转出 算 支出、 如果 转入算 收入
                throw new WalletException('当前操作不支持转账');
                break;
            default:
                throw new WalletException('当前操作不支持转账');
                break;
        }

        // 记录余额
        $transaction->balance = $wallet->balance;
        $transaction->freeze  = $wallet->freeze;

        $wallet->transaction($transaction);

        return $wallet;


    }

    /**
     * @param  int  $id
     * @param  WalletActionDTO  $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     */
    public function recharge(int $id, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type = TransactionTypeEnum::RECHARGE;
        return $this->doAction($id, $DTO);
    }

    /**
     * @param  int  $id
     * @param  WalletActionDTO  $DTO
     * @param  bool  $balanceAllowNegative
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    protected function doAction(int $id, WalletActionDTO $DTO, bool $balanceAllowNegative = false) : WalletTransaction
    {
        $DTO->amount = bcadd($DTO->amount, 0, 2);
        $this->validate($DTO);
        switch ($DTO->type) {
            case TransactionTypeEnum::REFUND:
            case TransactionTypeEnum::RECHARGE:
                $DTO->amount = $DTO->amount;
                break;
            case TransactionTypeEnum::PAYMENT:
            case TransactionTypeEnum::WITHDRAWAL:
                $DTO->amount = bcmul($DTO->amount, -1, 2);
                break;
            case TransactionTypeEnum::TRANSFER:
                throw new WalletException('当前操作不支持转账');
                break;
        }
        $direction = bccomp($DTO->amount, 0, 2) > 0 ? AmountDirectionEnum::INCOME : AmountDirectionEnum::EXPENSE;
        try {
            DB::beginTransaction();
            $wallet = $this->findLock($id);
            $this->isAllowAction($wallet, $DTO->type);
            // 余额记录
            $wallet->balance = bcadd($wallet->balance, $DTO->amount, 2);
            if ($balanceAllowNegative === false && bccomp($wallet->balance, 0, 2) < 0) {
                throw new  WalletException('余额不足');
            }
            $wallet->save();
            $transaction                   = new WalletTransaction();
            $transaction->id               = $this->buildID();
            $transaction->amount           = $DTO->amount;
            $transaction->direction        = $direction;
            $transaction->status           = TransactionStatusEnum::SUCCESS;
            $transaction->transaction_type = $DTO->type;
            $transaction->title            = $DTO->title;
            $transaction->description      = $DTO->description;
            $transaction->bill_type        = $DTO->billType;
            $transaction->business_id      = $DTO->businessId;
            $wallet->transactions()->save($transaction);
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $transaction;
    }


    /**
     * @param  Wallet  $wallet
     * @param  TransactionTypeEnum  $transactionType
     *
     * @return bool
     * @throws WalletException
     */
    public function isAllowAction(Wallet $wallet, TransactionTypeEnum $transactionType) : bool
    {
        if ($wallet->status === WalletStatusEnum::DISABLE && !in_array($transactionType, [
                TransactionTypeEnum::PAYMENT,
                TransactionTypeEnum::TRANSFER,
            ], true)) {
            throw new WalletException('使用受限');
        }
        return true;
    }

    public function disable(int $id) : Wallet
    {
        $wallet         = $this->find($id);
        $wallet->status = WalletStatusEnum::DISABLE;
        $wallet->save();
        return $wallet;
    }

    public function enable(int $id) : Wallet
    {
        $wallet         = $this->find($id);
        $wallet->status = WalletStatusEnum::ENABLE;
        $wallet->save();
        return $wallet;
    }

    /**
     * 冻结金额
     *
     * @param  int  $id
     * @param  WalletActionDTO  $DTO
     * @param  bool  $forceFreeze
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function freeze(int $id, WalletActionDTO $DTO, bool $forceFreeze = false) : WalletTransaction
    {
        $DTO->type   = TransactionTypeEnum::FROZEN;
        $DTO->amount = bcadd($DTO->amount, 0, 2);
        $this->validate($DTO);

        try {
            DB::beginTransaction();
            $wallet = $this->findLock($id);
            $this->isAllowAction($wallet, $DTO->type);
            // 余额记录
            $wallet->balance = bcadd($wallet->balance, -$DTO->amount, 2);
            if ($forceFreeze === false && (bccomp($wallet->balance, 0, 2) < 0)) {
                throw new WalletException('余额不足');
            }
            $wallet->freeze = bcadd($wallet->freeze, $DTO->amount, 2);
            $wallet->save();
            $transaction                   = new WalletTransaction();
            $transaction->id               = $this->buildID();
            $transaction->amount           = -$DTO->amount;
            $transaction->direction        = AmountDirectionEnum::EXPENDITURE;
            $transaction->status           = TransactionStatusEnum::SUCCESS;
            $transaction->transaction_type = $DTO->type;
            $transaction->title            = $DTO->title;
            $transaction->description      = $DTO->description;
            $transaction->bill_type        = $DTO->billType;
            $transaction->business_id      = $DTO->businessId;
            $wallet->transactions()->save($transaction);
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $transaction;
    }

    /**
     * 强制冻结
     *
     * @param  int  $id
     * @param  WalletActionDTO  $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function forceFreeze(int $id, WalletActionDTO $DTO) : WalletTransaction
    {
        return $this->freeze($id, $DTO, true);
    }

    /**
     * 解冻
     *
     * @param  int  $id
     * @param  WalletActionDTO  $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function unfreeze(int $id, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type   = TransactionTypeEnum::UNFROZEN;
        $DTO->amount = bcadd($DTO->amount, 0, 2);
        $this->validate($DTO);
        try {
            DB::beginTransaction();
            $wallet = $this->findLock($id);
            $this->isAllowAction($wallet, $DTO->type);
            // 余额记录
            $wallet->balance = bcadd($wallet->balance, $DTO->amount, 2);
            $wallet->freeze  = bcadd($wallet->freeze, -$DTO->amount, 2);
            if (bccomp($wallet->freeze, 0, 2) < 0) {
                throw new WalletException('冻结余额不足');
            }
            $wallet->save();
            $transaction                   = new WalletTransaction();
            $transaction->id               = $this->buildID();
            $transaction->amount           = $DTO->amount;
            $transaction->direction        = AmountDirectionEnum::INCOME;
            $transaction->status           = TransactionStatusEnum::SUCCESS;
            $transaction->transaction_type = $DTO->type;
            $transaction->title            = $DTO->title;
            $transaction->description      = $DTO->description;
            $transaction->bill_type        = $DTO->billType;
            $transaction->business_id      = $DTO->businessId;
            $wallet->transactions()->save($transaction);
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $transaction;
    }


    /**
     * @param  int  $outWalletId
     * @param  int  $intoWalletId
     * @param  WalletActionDTO  $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function transfer(int $outWalletId, int $intoWalletId, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type   = TransactionTypeEnum::TRANSFER;
        $DTO->amount = bcadd($DTO->amount, 0, 2);
        $this->validate($DTO);
        try {
            DB::beginTransaction();
            $outWallet = $this->findLock($outWalletId);
            $this->isAllowAction($outWallet, $DTO->type);
            if (bccomp($outWallet->balance, $DTO->amount, 2) < 0) {
                throw new WalletException('余额不足');
            }
            $outWallet->balance = bcadd($outWallet->balance, -$DTO->amount, 2);
            $outWallet->save();
            $intoWallet = $this->findLock($intoWalletId);
            $this->isAllowAction($intoWallet, $DTO->type);
            $intoWallet->balance = bcadd($intoWallet->balance, $DTO->amount, 2);
            $intoWallet->save();

            $outTransaction                   = new WalletTransaction();
            $outTransaction->id               = $this->buildID();
            $outTransaction->amount           = -$DTO->amount;
            $outTransaction->direction        = AmountDirectionEnum::EXPENSE;
            $outTransaction->status           = TransactionStatusEnum::SUCCESS;
            $outTransaction->transaction_type = $DTO->type;
            $outTransaction->title            = $DTO->title;
            $outTransaction->description      = $DTO->description;
            $outTransaction->bill_type        = $DTO->billType;
            $outTransaction->business_id      = $DTO->businessId;
            $outWallet->transactions()->save($outTransaction);

            $intoTransaction                   = new WalletTransaction();
            $intoTransaction->id               = $this->buildID();
            $intoTransaction->amount           = $DTO->amount;
            $intoTransaction->direction        = AmountDirectionEnum::INCOME;
            $intoTransaction->status           = TransactionStatusEnum::SUCCESS;
            $intoTransaction->transaction_type = $DTO->type;
            $intoTransaction->title            = $DTO->title;
            $intoTransaction->description      = $DTO->description;
            $intoTransaction->bill_type        = $DTO->billType;
            $intoTransaction->business_id      = $DTO->businessId;
            $intoWallet->transactions()->save($intoTransaction);
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $outTransaction;
    }


}
