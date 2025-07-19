<?php

namespace RedJasmine\Wallet\Infrastructure\Services;

use RedJasmine\Payment\Application\Services\Trade\Commands\TradeCreateCommand;
use RedJasmine\Payment\Application\Services\Trade\TradeApplicationService;
use RedJasmine\Payment\Application\Services\Transfer\Commands\TransferCreateCommand;
use RedJasmine\Payment\Application\Services\Transfer\TransferApplicationService;
use RedJasmine\Payment\Domain\Data\TransferPayee;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Wallet\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTransferData;
use RedJasmine\Wallet\Domain\Data\Payment\WalletTradeData;
use RedJasmine\Wallet\Domain\Data\Payment\WalletTransferData;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;

class PaymentServiceIntegration implements PaymentServiceInterface
{

    public function __construct(
        protected TradeApplicationService $tradeApplicationService,
        protected TransferApplicationService $transferApplicationService,
    ) {
    }

    /**
     * 创建付款单
     *
     * @param  WalletTradeData  $paymentData
     *
     * @return PaymentTradeData
     */
    public function createTrade(WalletTradeData $paymentData) : PaymentTradeData
    {
        $command                       = new TradeCreateCommand();
        $command->merchantAppId        = 1; // TODO 根据配置
        $command->merchantTradeNo      = $paymentData->businessNo;
        $command->merchantTradeOrderNo = $paymentData->businessNo;
        $command->amount               = $paymentData->amount;
        $command->subject              = '充值';

        $trade = $this->tradeApplicationService->create($command);

        $tradeData                = new PaymentTradeData();
        $tradeData->paymentStatus = PaymentStatusEnum::PAYING;
        $tradeData->setKey($paymentData->businessNo);
        $tradeData->amount      = $paymentData->amount;
        $tradeData->paymentType = 'payment';
        $tradeData->paymentId   = $trade->trade_no;
        $tradeData->context     = $this->tradeApplicationService->getSdkResult($trade)->toArray();
        return $tradeData;
    }

    public function createTransfer(WalletTransferData $walletTransferData) : PaymentTransferData
    {

        $command                     = new TransferCreateCommand();
        $command->isAutoExecute      = false;
        $command->sceneCode          = TransferSceneEnum::OTHER;
        $command->subject            = '测试转账';
        $command->merchantAppId      = 587705207249301; // TODO 根据配置
        $command->channelAppId       = 9021000140685250; // TODO 根据配置
        $command->merchantTransferNo = $walletTransferData->businessNo;
        $command->methodCode         = $walletTransferData->payee->channel;
        $command->amount             = $walletTransferData->amount;


        $command->payee = TransferPayee::from([
            'identityType' => $walletTransferData->payee->accountType,
            'identityId'   => $walletTransferData->payee->accountNo,
            'name'         => $walletTransferData->payee->name,
            'certType'     => $walletTransferData->payee->certType,
            'certNo'       => $walletTransferData->payee->certNo,

        ]);

        $transfer            = $this->transferApplicationService->create($command);
        $paymentTransferData = new PaymentTransferData();
        $paymentTransferData->setKey($walletTransferData->businessNo);
        $paymentTransferData->paymentStatus = PaymentStatusEnum::PAYING;
        $paymentTransferData->paymentType   = 'payment';
        $paymentTransferData->paymentId     = $transfer->transfer_no;

        return $paymentTransferData;
    }


    public function paying()
    {
        // TODO: Implement paying() method.
    }

    public function queryPayment()
    {
        // TODO: Implement queryPayment() method.
    }

    public function getBankCard(UserInterface $user, string $bankCardId)
    {
        // TODO: Implement getBankCard() method.
    }

    public function getBankCards(UserInterface $user)
    {
        // TODO: Implement getBankCards() method.
    }


}