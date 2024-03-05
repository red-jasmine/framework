<?php

namespace RedJasmine\Wallet\Actions\Withdrawals;

use Illuminate\Support\Facades\DB;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Wallet\DataTransferObjects\WalletActionDTO;
use RedJasmine\Wallet\DataTransferObjects\Withdrawals\WalletWithdrawalDTO;
use RedJasmine\Wallet\Enums\Withdrawals\WithdrawalStatusEnum;
use RedJasmine\Wallet\Models\Wallet;
use RedJasmine\Wallet\Models\WalletWithdrawal;
use RedJasmine\Wallet\WalletWithdrawalService;
use Throwable;

class WithdrawalCreateAction extends Action
{

    public WalletWithdrawalService $service;

    protected ?string $pipelinesConfigKey = 'red-jasmine.wallet.pipelines.withdrawals.create';


    public function isAllow(Wallet $wallet) : bool
    {

        return true;
    }

    public function execute(int $id, WalletWithdrawalDTO $DTO)
    {
        $wallet = $this->service->walletService->find($id);
        $this->isAllow($wallet);
        $wallet->setDTO($DTO);

        $this->pipelines($wallet);
        $this->pipeline->before();
        try {
            DB::beginTransaction();
            $this->pipeline->then(fn(Wallet $wallet) => $this->create($wallet, $DTO));
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        $this->pipeline->after();

    }

    protected function create(Wallet $wallet, WalletWithdrawalDTO $DTO)
    {
        $amount = bcadd($DTO->amount, $DTO->fee, 2);
        // 对钱包余额冻结
        $freezeDTO = WalletActionDTO::from([
                                               'amount' => $amount,
                                               'title'  => '提现'
                                           ]);
        $this->service->walletService->freeze($wallet->id, $freezeDTO);
        // 创建提现单

        $walletWithdrawal                             = new WalletWithdrawal();
        $walletWithdrawal->id                         = $this->service->buildID();
        $walletWithdrawal->owner                      = $wallet->owner;
        $walletWithdrawal->amount                     = $DTO->amount;
        $walletWithdrawal->fee                        = $DTO->fee;
        $walletWithdrawal->pay_amount                 = $amount;
        $walletWithdrawal->transfer_type              = $DTO->transferType;
        $walletWithdrawal->transfer_account           = $DTO->transferAccount;
        $walletWithdrawal->transfer_account_real_name = $DTO->transferAccountRealName;
        $walletWithdrawal->status                     = WithdrawalStatusEnum::PROCESSING;
        $walletWithdrawal->save();
    }

}
