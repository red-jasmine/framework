<?php

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalApprovalCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalTransferCallbackCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;
use RedJasmine\Wallet\Domain\Models\ValueObjects\Payee;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;

beforeEach(function () {
    $this->WalletCommandService           = app(WalletApplicationService::class);
    $this->WalletRepository               = app(WalletRepositoryInterface::class);
    $this->WalletWithdrawalCommandService = app(WalletWithdrawalApplicationService::class);
    $this->WalletWithdrawalRepository     = app(WalletWithdrawalRepositoryInterface::class);
    $this->type                           = 'commission';
    $this->currency                       = 'ZCM';
    $this->chanteCurrency                 = 'CNY';

    $payee              = new Payee();
    $payee->channel     = 'alipay';
    $payee->accountType = 'LOGIN_ID';
    $payee->accountNo   = 'sildsg4556@sandbox.com';
    $payee->name        = 'sildsg4556';
    $payee->certType    = 'ID_CARD';
    $payee->certNo      = '933396192809243496';

    $this->payee = $payee;
});
test('can create a wallet', function () {

    $command = new WalletCreateCommand();

    $command->owner = \Illuminate\Support\Facades\Auth::user();

    $command->type = $this->type;

    $command->currency = $this->currency;


    if ($wallet = $this->WalletRepository->findByOwnerType($command->owner, $command->type)) {
        $this->assertEquals($command->type, $wallet->type);
        return $wallet;
    }

    $wallet = $result = $this->WalletCommandService->create($command);

    $this->assertEquals($command->type, $result->type);
    $this->assertEquals(0, $result->balance->getAmount());
    $this->assertEquals(0, $result->freeze->getAmount());
    return $wallet;

});

test('cna wallet add income', function (Wallet $wallet) {
    // 加宽
    $command = new WalletTransactionCommand();
    $command->setKey($wallet->id);
    $command->amount = Money::parse(fake()->numberBetween(1000000, 2000000), $this->currency);

    $command->direction       = AmountDirectionEnum::INCOME;
    $command->transactionType = TransactionTypeEnum::RECHARGE;
    $result                   = $this->WalletCommandService->transaction($command);


    $this->assertEquals($command->amount->getAmount(), $result->amount->getAmount(), 0);


    return $this->WalletRepository->findByOwnerType(\Illuminate\Support\Facades\Auth::user(), $this->type);

})->depends('can create a wallet');


test('can create withdrawal', function (Wallet $wallet) {

    $payee              = $this->payee;


    $command = new WalletWithdrawalCreateCommand();
    $command->setKey($wallet->id);
    $command->amount   = Money::parse(10000, $this->currency);
    $command->currency = $this->chanteCurrency;
    $command->payee    = $payee;
    $withdrawal        = $this->WalletWithdrawalCommandService->create($command);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);
    $this->assertEquals($wallet->id, $withdrawal->wallet_id);
    $this->assertEquals($wallet->owner_type, $withdrawal->owner_type);
    $this->assertEquals($wallet->owner_id, $withdrawal->owner_id);
    $this->assertEquals($command->amount->getAmount(), $withdrawal->amount->getAmount());


    return $withdrawal;
})->depends('cna wallet add income');

test('can approval pass a withdrawal', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalApprovalCommand();

    $command->setKey($withdrawal->withdrawal_no);

    $command->status = ApprovalStatusEnum::PASS;
    $command->message        = fake()->text(20);

    $result = $this->WalletWithdrawalCommandService->approval($command);
    $this->assertEquals(true, $result);

    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::PASS, $withdrawal->approval_status);
    $this->assertEquals($command->message, $withdrawal->approval_message);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);

    return $withdrawal;
})->depends('can create withdrawal');



test('can payment success a withdrawal', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalTransferCallbackCommand();

    $command->setKey($withdrawal->withdrawal_no);

    $command->paymentStatus         = PaymentStatusEnum::SUCCESS;
    $command->paymentType           = 'payment';
    $command->paymentId             = fake()->numerify('############');
    $command->paymentChannelTradeNo = fake()->numerify('############');

    $result = $this->WalletWithdrawalCommandService->transferCallback($command);
    $this->assertEquals(true, $result);


    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::PASS, $withdrawal->approval_status);

    $this->assertEquals(WithdrawalStatusEnum::SUCCESS, $withdrawal->status);
    $this->assertEquals(PaymentStatusEnum::SUCCESS, $withdrawal->payment_status);


})->depends('can approval pass a withdrawal');


test('can create withdrawal2', function (Wallet $wallet) {

    $payee              = $this->payee;

    $command = new WalletWithdrawalCreateCommand();
    $command->setKey($wallet->id);
    $command->amount   = Money::parse(10000, $this->currency);
    $command->currency = $this->chanteCurrency;
    $command->payee    = $payee;
    $withdrawal        = $this->WalletWithdrawalCommandService->create($command);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);
    $this->assertEquals($wallet->id, $withdrawal->wallet_id);
    $this->assertEquals($wallet->owner_type, $withdrawal->owner_type);
    $this->assertEquals($wallet->owner_id, $withdrawal->owner_id);
    $this->assertEquals($command->amount->getAmount(), $withdrawal->amount->getAmount());


    return $withdrawal;
})->depends('cna wallet add income');

test('can approval reject a withdrawal', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalApprovalCommand();

    $command->setKey($withdrawal->withdrawal_no);

    $command->status = ApprovalStatusEnum::REJECT;
    $command->message        = fake()->text();
    $result                  = $this->WalletWithdrawalCommandService->approval($command);
    $this->assertEquals(true, $result);

    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::REJECT, $withdrawal->approval_status);
    $this->assertEquals($command->message, $withdrawal->approval_message);
    $this->assertEquals(WithdrawalStatusEnum::FAIL, $withdrawal->status);


})->depends('can create withdrawal2');

test('can create withdrawal3', function (Wallet $wallet) {


    $payee              = $this->payee;
    $command = new WalletWithdrawalCreateCommand();
    $command->setKey($wallet->id);
    $command->amount   = Money::parse(10000, $this->currency);
    $command->currency = $this->chanteCurrency;
    $command->payee    = $payee;
    $withdrawal        = $this->WalletWithdrawalCommandService->create($command);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);
    $this->assertEquals($wallet->id, $withdrawal->wallet_id);
    $this->assertEquals($wallet->owner_type, $withdrawal->owner_type);
    $this->assertEquals($wallet->owner_id, $withdrawal->owner_id);
    $this->assertEquals($command->amount->getAmount(), $withdrawal->amount->getAmount());


    return $withdrawal;
})->depends('cna wallet add income');

test('can approval revoke a withdrawal', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalApprovalCommand();

    $command->setKey($withdrawal->withdrawal_no);

    $command->status = ApprovalStatusEnum::REVOKE;
    $command->message        = fake()->text();

    $result = $this->WalletWithdrawalCommandService->approval($command);
    $this->assertEquals(true, $result);

    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::REVOKE, $withdrawal->approval_status);
    $this->assertEquals($command->message, $withdrawal->approval_message);
    $this->assertEquals(WithdrawalStatusEnum::FAIL, $withdrawal->status);


})->depends('can create withdrawal3');
