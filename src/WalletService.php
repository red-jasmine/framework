<?php

namespace RedJasmine\Wallet;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\RedJasmine\Wallet\Models\_IH_Wallet_C;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;
use RedJasmine\Wallet\DataTransferObjects\WalletActionDTO;
use RedJasmine\Wallet\Enums\AmountDirection;
use RedJasmine\Wallet\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Enums\WalletStatusEnum;
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
     * @param UserInterface $owner
     * @param string        $walletType
     *
     * @return Wallet
     * @throws Exception
     */
    public function wallet(UserInterface $owner, string $walletType) : Wallet
    {
        return $this->create($owner, $walletType);

    }

    /**
     * @param UserInterface $owner
     *
     * @return Collection|array|Wallet[]
     */
    public function walletsByOwner(UserInterface $owner) : Collection
    {
        return Wallet::onlyOwner($owner)->get();
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
     * @param bool            $balanceAllowNegative
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
            case TransactionTypeEnum::FROZEN:
            case TransactionTypeEnum::UNFROZEN:
            case TransactionTypeEnum::TRANSFER:
                throw new WalletException('当前操作不支持转账');
                break;
        }
        $direction = bccomp($DTO->amount, 0, 2) > 0 ? AmountDirection::INCOME : AmountDirection::EXPENDITURE;
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
     * 扣费
     *
     * @param int             $id
     * @param WalletActionDTO $DTO
     *
     * @return WalletTransaction
     * @throws AbstractException
     * @throws Throwable
     * @throws WalletException
     */
    public function charge(int $id, WalletActionDTO $DTO) : WalletTransaction
    {
        $DTO->type = TransactionTypeEnum::PAYMENT;
        return $this->doAction($id, $DTO, true);
    }


    /**
     * @param Wallet              $wallet
     * @param TransactionTypeEnum $transactionType
     *
     * @return bool
     * @throws WalletException
     */
    public function isAllowAction(Wallet $wallet, TransactionTypeEnum $transactionType) : bool
    {
        if ($wallet->status === WalletStatusEnum::DISABLE && !in_array($transactionType, [
                TransactionTypeEnum::PAYMENT,
                TransactionTypeEnum::TRANSFER,
            ],                                                         true)) {
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
     * @param int             $id
     * @param WalletActionDTO $DTO
     * @param bool            $forceFreeze
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
            $transaction->direction        = AmountDirection::EXPENDITURE;
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
     * @param int             $id
     * @param WalletActionDTO $DTO
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
     * @param int             $id
     * @param WalletActionDTO $DTO
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
            $transaction->direction        = AmountDirection::INCOME;
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
     * @param int             $outWalletId
     * @param int             $intoWalletId
     * @param WalletActionDTO $DTO
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
            $outTransaction->direction        = AmountDirection::EXPENDITURE;
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
            $intoTransaction->direction        = AmountDirection::INCOME;
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
        return Wallet::firstOrCreate($attributes, [
            'id'     => $this->buildID(),
            'status' => WalletStatusEnum::ENABLE
        ]);
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
