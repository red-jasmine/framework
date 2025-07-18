<?php

use Cknow\Money\Money;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalApprovalCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalPaymentCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalApplicationService;
use RedJasmine\Wallet\Domain\Data\Payee;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalPaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;

beforeEach(function () {
    $this->WalletCommandService           = app(WalletApplicationService::class);
    $this->WalletRepository               = app(WalletRepositoryInterface::class);
    $this->WalletWithdrawalCommandService = app(WalletWithdrawalApplicationService::class);
    $this->WalletWithdrawalRepository     = app(WalletWithdrawalRepositoryInterface::class);
    $this->type                           = 'point';
    $this->currency                       = 'CNY';
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
    $this->assertEquals(0, bccomp($result->balance, 0, 0));
    $this->assertEquals(0, bccomp($result->freeze, 0, 0));
    return $wallet;

});

test('cna wallet add income', function (Wallet $wallet) {
    // 加宽
    $command                  = new WalletTransactionCommand();
    $command->id              = $wallet->id;
    $command->amount          = Money::parse(fake()->numberBetween(10000, 20000), $this->currency);
    $command->direction       = AmountDirectionEnum::INCOME;
    $command->transactionType = TransactionTypeEnum::RECHARGE;
    $result                   = $this->WalletCommandService->transaction($command);


    $this->assertEquals($command->amount->getAmount(), $result->amount->getAmount(), 0);


    return $this->WalletRepository->findByOwnerType(\Illuminate\Support\Facades\Auth::user(), $this->type);

})->depends('can create a wallet');


test('can create withdrawal1', function (Wallet $wallet) {

    $payee              = new Payee();
    $payee->channel     = 'alipay';
    $payee->accountType = 'account';
    $payee->accountNo   = 'xxxx@qq.com';
    $payee->name        = fake()->name();
    $payee->certType    = 'id';
    $payee->certNo      = fake()->numerify('#########');

    $command         = new WalletWithdrawalCreateCommand();
    $command->id     = $wallet->id;
    $command->amount = Money::parse(fake()->numberBetween(1000, 2000), $this->currency);

    $command->payee = $payee;
    $withdrawal     = $this->WalletWithdrawalCommandService->create($command);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);
    $this->assertEquals($wallet->id, $withdrawal->wallet_id);
    $this->assertEquals($wallet->owner_type, $withdrawal->owner_type);
    $this->assertEquals($wallet->owner_id, $withdrawal->owner_id);
    $this->assertEquals($command->amount->getAmount(), $withdrawal->amount->getAmount());


    return $withdrawal;
})->depends('cna wallet add income');

test('can approval pass a withdrawal1', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalApprovalCommand();

    $command->withdrawalNo = $withdrawal->withdrawal_no;

    $command->status  = ApprovalStatusEnum::PASS;
    $command->message = fake()->text();

    $result = $this->WalletWithdrawalCommandService->approval($command);
    $this->assertEquals(true, $result);

    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::PASS, $withdrawal->approval_status);
    $this->assertEquals($command->message, $withdrawal->approval_message);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);

    return $withdrawal;
})->depends('can create withdrawal1');
test('can payment paying a withdrawal', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalPaymentCommand();

    $command->withdrawalNo = $withdrawal->withdrawal_no;

    $command->paymentStatus         = WithdrawalPaymentStatusEnum::PAYING;
    $command->paymentType           = 'payment';
    $command->paymentId             = fake()->numerify('############');
    $command->paymentChannelTradeNo = fake()->numerify('############');

    $result = $this->WalletWithdrawalCommandService->payment($command);
    $this->assertEquals(true, $result);


    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::PASS, $withdrawal->approval_status);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);
    $this->assertEquals(WithdrawalPaymentStatusEnum::PAYING, $withdrawal->payment_status);


})->depends('can approval pass a withdrawal1');

test('can payment success a withdrawal', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalPaymentCommand();

    $command->withdrawalNo = $withdrawal->withdrawal_no;

    $command->paymentStatus         = WithdrawalPaymentStatusEnum::SUCCESS;
    $command->paymentType           = 'payment';
    $command->paymentId             = fake()->numerify('############');
    $command->paymentChannelTradeNo = fake()->numerify('############');

    $result = $this->WalletWithdrawalCommandService->payment($command);
    $this->assertEquals(true, $result);


    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::PASS, $withdrawal->approval_status);

    $this->assertEquals(WithdrawalStatusEnum::SUCCESS, $withdrawal->status);
    $this->assertEquals(WithdrawalPaymentStatusEnum::SUCCESS, $withdrawal->payment_status);


})->depends('can approval pass a withdrawal1');


test('can create withdrawal2', function (Wallet $wallet) {

    $payee              = new Payee();
    $payee->channel     = 'alipay';
    $payee->accountType = 'account';
    $payee->accountNo   = 'xxxx@qq.com';
    $payee->name        = fake()->name();
    $payee->certType    = 'id';
    $payee->certNo      = fake()->numerify('#########');

    $command         = new WalletWithdrawalCreateCommand();
    $command->id     = $wallet->id;
    $command->amount = Money::parse(fake()->numberBetween(1000, 2000), $this->currency);

    $command->payee = $payee;
    $withdrawal     = $this->WalletWithdrawalCommandService->create($command);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);
    $this->assertEquals($wallet->id, $withdrawal->wallet_id);
    $this->assertEquals($wallet->owner_type, $withdrawal->owner_type);
    $this->assertEquals($wallet->owner_id, $withdrawal->owner_id);
    $this->assertEquals($command->amount->getAmount(), $withdrawal->amount->getAmount());


    return $withdrawal;
})->depends('cna wallet add income');

test('can approval pass a withdrawal2', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalApprovalCommand();

    $command->withdrawalNo = $withdrawal->withdrawal_no;

    $command->status  = ApprovalStatusEnum::PASS;
    $command->message = fake()->text();

    $result = $this->WalletWithdrawalCommandService->approval($command);
    $this->assertEquals(true, $result);

    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::PASS, $withdrawal->approval_status);
    $this->assertEquals($command->message, $withdrawal->approval_message);
    $this->assertEquals(WithdrawalStatusEnum::PROCESSING, $withdrawal->status);

    return $withdrawal;
})->depends('can create withdrawal2');

test('can payment fail a withdrawal', function (WalletWithdrawal $withdrawal) {

    $command = new WalletWithdrawalPaymentCommand();

    $command->withdrawalNo = $withdrawal->withdrawal_no;

    $command->paymentStatus         = WithdrawalPaymentStatusEnum::FAIL;
    $command->paymentType           = 'payment';
    $command->paymentId             = fake()->numerify('############');
    $command->paymentChannelTradeNo = fake()->numerify('############');

    $result = $this->WalletWithdrawalCommandService->payment($command);
    $this->assertEquals(true, $result);


    $withdrawal = $this->WalletWithdrawalRepository->findByNo($withdrawal->withdrawal_no);
    $this->assertEquals(ApprovalStatusEnum::PASS, $withdrawal->approval_status);

    $this->assertEquals(WithdrawalStatusEnum::FAIL, $withdrawal->status);
    $this->assertEquals(WithdrawalPaymentStatusEnum::FAIL, $withdrawal->payment_status);


})->depends('can approval pass a withdrawal2');