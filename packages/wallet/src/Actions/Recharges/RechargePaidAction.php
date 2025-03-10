<?php

namespace RedJasmine\Wallet\Actions\Recharges;

use Illuminate\Support\Facades\DB;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Foundation\Service\Action;
use RedJasmine\Wallet\DataTransferObjects\Recharges\RechargePaymentDTO;
use RedJasmine\Wallet\DataTransferObjects\WalletActionDTO;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Exceptions\WalletException;
use RedJasmine\Wallet\WalletRechargeService;
use Throwable;

class RechargePaidAction extends Action
{

    public WalletRechargeService $service;

    protected ?string $pipelinesConfigKey = 'red-jasmine.wallet.pipelines.recharges.paid';

    /**
     * @param WalletRecharge $walletRecharge
     *
     * @return bool
     * @throws WalletException
     */
    public function isAllow(WalletRecharge $walletRecharge) : bool
    {

        if (in_array($walletRecharge->status, [
            RechargeStatusEnum::PAID,
            RechargeStatusEnum::SUCCESS,
        ],           true)) {
            throw new WalletException('当前状态不可操作');
        }

        return true;
    }

    /**
     * 支付成功
     *
     * @throws AbstractException
     * @throws WalletException
     * @throws Throwable
     */
    public function execute(int $id, RechargePaymentDTO $DTO)
    {

        try {
            DB::beginTransaction();

            $walletRecharge = $this->service->findLock($id);
            $walletRecharge->setDTO($DTO);

            $this->isAllow($walletRecharge);

            $this->pipelines($walletRecharge);
            $this->pipeline->before();
            $walletRecharge = $this->pipeline->then(fn(WalletRecharge $walletRecharge) => $this->paid($walletRecharge, $DTO));

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

    /**
     * 充值单支付成功
     *
     * @param WalletRecharge     $walletRecharge
     * @param RechargePaymentDTO $DTO
     *
     * @return WalletRecharge
     * @throws AbstractException
     * @throws Throwable
     */
    protected function paid(WalletRecharge $walletRecharge, RechargePaymentDTO $DTO) : WalletRecharge
    {
        $walletRecharge->status                   = RechargeStatusEnum::PAID;
        $walletRecharge->payment_type             = $DTO->paymentType;
        $walletRecharge->payment_id               = $DTO->paymentId;
        $walletRecharge->payment_channel_trade_no = $DTO->paymentChannelTradeNo;
        $walletRecharge->payment_mode             = $DTO->paymentMode;
        $walletRecharge->payment_time             = now();

        $rechargeDTO = WalletActionDTO::from([
                                                 'amount'      => $walletRecharge->amount,
                                                 'title'       => '充值',
                                                 'description' => '',
                                                 'billType'    => '',
                                                 'businessId'  => $walletRecharge->id,
                                             ]);
        $this->service->walletService->recharge($walletRecharge->wallet_id, $rechargeDTO);
        $walletRecharge->status = RechargeStatusEnum::SUCCESS;
        $walletRecharge->save();
        return $walletRecharge;
    }
}
