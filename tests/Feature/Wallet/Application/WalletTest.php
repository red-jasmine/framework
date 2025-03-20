<?php

use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use RedJasmine\Wallet\Application\Services\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\WalletCommandService;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Exceptions\WalletException;

beforeEach(function () {
    $this->WalletCommandService = app(WalletCommandService::class);
    $this->WalletRepository     = app(WalletRepositoryInterface::class);

    $this->type     = 'point';
    $this->currency = 'CNY';
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

test('cna wallet transactions', function (Wallet $wallet) {


    $command     = new WalletTransactionCommand();
    $command->id = $wallet->id;

    // 收入
    $initBalance = $wallet->balance;
    $initFreeze  = $wallet->freeze ?? 0.00;

    $command->amount          = new Amount(fake()->numberBetween(10000, 20000), $this->currency);
    $command->direction       = AmountDirectionEnum::INCOME;
    $command->transactionType = TransactionTypeEnum::RECHARGE;
    $result                   = $this->WalletCommandService->transaction($command);
    $this->assertEquals($command->amount->total(), $result->amount->total());
    $this->assertEquals(bcadd($initBalance, $command->amount->total(), 2), $result->balance);


    // 支出
    $initBalance              = $result->balance;
    $command->amount          = new Amount(fake()->numberBetween(1000, 2000), $this->currency);
    $command->direction       = AmountDirectionEnum::EXPENSE;
    $command->transactionType = TransactionTypeEnum::PAYMENT;
    $result                   = $this->WalletCommandService->transaction($command);
    $this->assertEquals($command->amount->total(), -$result->amount->total());
    $this->assertEquals(bcsub($initBalance, $command->amount->total(), 2), $result->balance);


    // 冻结余额
    $initBalance              = $result->balance;
    $freeze                   = new Amount(fake()->numberBetween(1000, 2000), $this->currency);
    $command->amount          = clone $freeze;
    $command->direction       = AmountDirectionEnum::FROZEN;
    $command->transactionType = TransactionTypeEnum::FROZEN;
    $result                   = $this->WalletCommandService->transaction($command);
    $this->assertEquals($command->amount->total(), -$result->amount->total());
    $this->assertEquals(bcsub($initBalance, $command->amount->total(), 2), $result->balance);
    $this->assertEquals(bcadd($command->amount->total(), $initFreeze, 2), $result->freeze);


    // 余额解冻
    $initBalance              = $result->balance;
    $command->amount          = clone $freeze;
    $command->direction       = AmountDirectionEnum::UNFROZEN;
    $command->transactionType = TransactionTypeEnum::UNFROZEN;
    $result                   = $this->WalletCommandService->transaction($command);
    $this->assertEquals($command->amount->total(), $result->amount->total());
    $this->assertEquals(bcadd($initBalance, $command->amount->total(), 2), $result->balance);
    $this->assertEquals($initFreeze, $result->freeze);




    $wallet = $this->WalletRepository->findByOwnerType(\Illuminate\Support\Facades\Auth::user(), $this->type);
    $this->assertEquals($result->balance, $wallet->balance);
    return $wallet;

})->depends('can create a wallet');


test('can create excess payment', function (Wallet $wallet) {

    $command                  = new WalletTransactionCommand();
    $command->id              = $wallet->id;
    $command->isAllowNegative = true;

    // 支付类型

    $amount                   = new Amount(fake()->numberBetween(10000000, 20000000), $this->currency);
    $initBalance              = $wallet->balance;
    $initFreeze               = $wallet->freeze;
    $command->amount          = clone $amount;
    $command->direction       = AmountDirectionEnum::EXPENSE;
    $command->transactionType = TransactionTypeEnum::PAYMENT;
    $result                   = $this->WalletCommandService->transaction($command);
    $this->assertEquals($command->amount->total(), -$result->amount->total());
    $this->assertEquals(bcsub($initBalance, $command->amount->total(), 2), $result->balance);

    // 充值回去
    $initBalance              = $result->balance;
    $initFreeze               = $result->freeze;
    $command->amount          = clone $amount;
    $command->direction       = AmountDirectionEnum::INCOME;
    $command->transactionType = TransactionTypeEnum::RECHARGE;
    $result                   = $this->WalletCommandService->transaction($command);
    $this->assertEquals($command->amount->total(), $result->amount->total());
    $this->assertEquals(bcadd($initBalance, $command->amount->total(), 2), $result->balance);


    $this->expectException(WalletException::class);
    $command->isAllowNegative = false;
    $amount                   = new Amount(fake()->numberBetween(10000000, 20000000), $this->currency);
    $initBalance              = $wallet->balance;
    $initFreeze               = $wallet->freeze;
    $command->amount          = clone $amount;
    $command->direction       = AmountDirectionEnum::EXPENSE;
    $command->transactionType = TransactionTypeEnum::PAYMENT;
    $result                   = $this->WalletCommandService->transaction($command);


})->depends('cna wallet transactions');
