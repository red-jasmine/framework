<?php

use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Wallet\WalletCommandService;
use RedJasmine\Wallet\Application\Services\Withdrawal\Commands\WalletWithdrawalCreateCommand;
use RedJasmine\Wallet\Application\Services\Withdrawal\WalletWithdrawalCommandService;
use RedJasmine\Wallet\Domain\Data\Payee;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;

beforeEach(function () {
    $this->WalletCommandService    = app(WalletCommandService::class);
    $this->WalletRepository        = app(WalletRepositoryInterface::class);
    $this->WalletWithdrawalService = app(WalletWithdrawalCommandService::class);
    $this->type                    = 'point';
    $this->currency                = 'CNY';
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
    $command->amount          = new Amount(fake()->numberBetween(10000, 20000), $this->currency);
    $command->direction       = AmountDirectionEnum::INCOME;
    $command->transactionType = TransactionTypeEnum::RECHARGE;
    $this->WalletCommandService->transaction($command);
    return $this->WalletRepository->findByOwnerType(\Illuminate\Support\Facades\Auth::user(), $this->type);

})->depends('can create a wallet');


test('can create withdrawal', function (Wallet $wallet) {

    $payee              = new Payee();
    $payee->channel     = 'alipay';
    $payee->accountType = 'account';
    $payee->accountNo   = 'xxxx@qq.com';
    $payee->name        = fake()->name();
    $payee->certType    = 'id';
    $payee->certNo      = fake()->numerify('#########');

    $command         = new WalletWithdrawalCreateCommand();
    $command->id     = $wallet->id;
    $command->amount = new Amount(fake()->numberBetween(1000, 2000), $this->currency);

    $command->payee = $payee;
    $withdrawal = $this->WalletWithdrawalService->create($command);


    dd($withdrawal);


})->depends('cna wallet add income');
