<?php

namespace RedJasmine\Wallet\Domain\Contracts;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTransferData;
use RedJasmine\Wallet\Domain\Data\Payment\WalletTradeData;
use RedJasmine\Wallet\Domain\Data\Payment\WalletTransferData;

/**
 * 支付服务协议
 */
interface PaymentServiceInterface
{


    // 需要返回  支付应用、支付单号、如何唤起支付页面
    /**
     * @param  WalletTradeData  $paymentData
     *
     * @return PaymentTradeData
     */
    public function createTrade(WalletTradeData $paymentData) : PaymentTradeData;
    // 注册收款回调


    // 付款+ 查询银行卡
    public function getBankCards(UserInterface $user);
    // 查询用户绑定银行卡
    // 创建付款单
    public function createTransfer(WalletTransferData $walletTransferData) : PaymentTransferData;


    // 查询支付结果
    public function queryPayment();

    // 获取选中银行卡 提现时采用
    public function getBankCard(UserInterface $user, string $bankCardId);


}