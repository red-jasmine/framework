<?php

namespace RedJasmine\Wallet\Actions\Recharges;

use Illuminate\Support\Facades\DB;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Wallet\DataTransferObjects\Recharges\WalletRechargeDTO;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\WalletRechargeService;
use Throwable;

/**
 * 发起充值
 */
class RechargeCreateAction extends Action
{

    public WalletRechargeService $service;

    protected ?string $pipelinesConfigKey = 'red-jasmine.wallet.pipelines.recharges.create';

    /**
     * 充值比例
     * @throws AbstractException
     * @throws Throwable
     */
    public function execute(int $walletId, WalletRechargeDTO $DTO) : WalletRecharge
    {
        $wallet                     = $this->service->walletService->find($walletId);
        $walletRecharge             = new WalletRecharge();
        $walletRecharge->id         = $this->service->buildID();
        $walletRecharge->wallet_id  = $wallet->id;
        $walletRecharge->owner      = $wallet->owner;
        $walletRecharge->amount     = $DTO->amount;
        $walletRecharge->pay_amount = $DTO->amount;
        $walletRecharge->status     = RechargeStatusEnum::CREATED;
        $walletRecharge->creator    = $this->service->getOperator();
        $this->pipelines($walletRecharge);
        $this->pipeline->before();
        try {
            DB::beginTransaction();
            $this->pipeline->then(fn(WalletRecharge $walletRecharge) => $this->create($walletRecharge));
            DB::commit();
        } catch (AbstractException $exception) {
            DB::rollBack();
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        $this->pipeline->after();

        return $walletRecharge;
    }

    protected function create(WalletRecharge $walletRecharge) : WalletRecharge
    {
        $walletRecharge->save();
        return $walletRecharge;
    }

}
