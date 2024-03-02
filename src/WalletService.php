<?php

namespace RedJasmine\Wallet;

use Exception;
use Illuminate\Support\Facades\DB;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Wallet\DataTransferObjects\WalletActionDTO;
use RedJasmine\Wallet\Enums\AmountDirection;
use RedJasmine\Wallet\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Exceptions\WalletException;
use RedJasmine\Wallet\Models\Wallet;
use RedJasmine\Wallet\Models\WalletTransaction;
use Throwable;


class WalletService extends Service
{
    protected static ?string $actionsConfigKey = 'red-jasmine.wallet.actions';


    public function find(int $id) : Wallet
    {
        return Wallet::findOrFail($id);
    }

    public function findLock(int $id) : Wallet
    {
        return Wallet::lockForUpdate()->findOrFail($id);
    }

    public function findByOwner(UserInterface $owner, string $walletType) : Wallet
    {
        return Wallet::onlyOwner($owner)->where('type', $walletType)->findOrFail();
    }

    /**
     * @param WalletActionDTO $DTO
     *
     * @return void
     * @throws WalletException
     */
    public function validate(WalletActionDTO $DTO) : void
    {

        if (bccomp($DTO->amount, 0, 2) <= 0) {
            throw new WalletException('充值必须大于0');
        }

    }

    /**
     * @param int             $id
     * @param WalletActionDTO $DTO
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
     * @param int             $id
     * @param WalletActionDTO $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    protected function doAction(int $id, WalletActionDTO $DTO) : WalletTransaction
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
            case TransactionTypeEnum::FROZEN:
            case TransactionTypeEnum::TRANSFER:
                throw new WalletException('当前操作不支持转账');
        }

        $direction = bccomp($DTO->amount, 0, 2) > 0 ? AmountDirection::INCOME : AmountDirection::EXPENDITURE;
        try {
            DB::beginTransaction();
            $wallet = $this->findLock($id);
            // 余额记录
            $wallet->balance = bcadd($wallet->balance, $DTO->amount, 2);
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
     * @param int             $id
     * @param WalletActionDTO $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     */
    public function refund(int $id, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type = TransactionTypeEnum::REFUND;
        return $this->doAction($id, $DTO);
    }

    /**
     * 支付
     *
     * @param int             $id
     * @param WalletActionDTO $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     */
    public function payment(int $id, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type = TransactionTypeEnum::PAYMENT;
        return $this->doAction($id, $DTO);
    }


    /**
     * @param int             $fromId
     * @param int             $toId
     * @param WalletActionDTO $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function transfer(int $fromId, int $toId, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type   = TransactionTypeEnum::TRANSFER;
        $DTO->amount = bcadd($DTO->amount, 0, 2);
        $this->validate($DTO);

        try {
            DB::beginTransaction();
            $fromWallet = $this->findLock($fromId);
            if (bccomp($fromWallet->balance, $DTO->amount, 2) < 0) {
                throw new WalletException('余额不足');
            }
            $fromWallet->balance = bcadd($fromWallet->balance, -$DTO->amount, 2);
            $fromWallet->save();
            $toWallet          = $this->findLock($toId);
            $toWallet->balance = bcadd($toWallet->balance, $DTO->amount, 2);
            $toWallet->save();

            $fromTransaction                   = new WalletTransaction();
            $fromTransaction->id               = $this->buildID();
            $fromTransaction->amount           = -$DTO->amount;
            $fromTransaction->direction        = AmountDirection::EXPENDITURE;
            $fromTransaction->status           = TransactionStatusEnum::SUCCESS;
            $fromTransaction->transaction_type = $DTO->type;
            $fromTransaction->title            = $DTO->title;
            $fromTransaction->description      = $DTO->description;
            $fromTransaction->bill_type        = $DTO->billType;
            $fromTransaction->business_id      = $DTO->businessId;
            $fromWallet->transactions()->save($fromTransaction);

            $toTransaction                   = new WalletTransaction();
            $toTransaction->id               = $this->buildID();
            $toTransaction->amount           = $DTO->amount;
            $toTransaction->direction        = AmountDirection::INCOME;
            $toTransaction->status           = TransactionStatusEnum::SUCCESS;
            $toTransaction->transaction_type = $DTO->type;
            $toTransaction->title            = $DTO->title;
            $toTransaction->description      = $DTO->description;
            $toTransaction->bill_type        = $DTO->billType;
            $toTransaction->business_id      = $DTO->businessId;
            $toWallet->transactions()->save($toTransaction);
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $fromTransaction;
    }

    /**
     * 提现
     *
     * @param int             $id
     * @param WalletActionDTO $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     */
    public function withdraw(int $id, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type = TransactionTypeEnum::WITHDRAWAL;
        return $this->doAction($id, $DTO);
    }

    /**
     * 创建
     *
     * @param UserInterface $owner
     * @param string        $walletType
     *
     * @return Wallet
     * @throws Exception
     */
    public function create(UserInterface $owner, string $walletType) : Wallet
    {
        $attributes = [
            'owner_type' => $owner->getType(),
            'owner_id'   => $owner->getID(),
            'type'       => $walletType
        ];
        return Wallet::firstOrCreate($attributes, [ 'id' => $this->buildID() ]);
    }


    /**
     * @return int
     * @throws Exception
     */
    public function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }
}
