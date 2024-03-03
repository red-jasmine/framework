<?php

namespace RedJasmine\Wallet\Actions\Withdrawals;

use Illuminate\Support\Facades\DB;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Wallet\DataTransferObjects\Withdrawals\WalletWithdrawalDTO;
use RedJasmine\Wallet\Models\Wallet;
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
        // 对钱包余额冻结

        // 创建提现单

    }

}
