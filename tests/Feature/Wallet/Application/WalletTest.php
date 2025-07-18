<?php


use Cknow\Money\Money;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletTransactionCommand;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Exceptions\WalletException;

beforeEach(function () {
    $this->WalletCommandService = app(WalletApplicationService::class);
    $this->WalletRepository     = app(WalletRepositoryInterface::class);

    $this->type     = 'integral';
    $this->currency = 'ZJF';
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
    $this->assertEquals(0, bccomp($result->balance->getAmount(), 0, 0));
    $this->assertEquals(0, bccomp($result->freeze->getAmount(), 0, 0));


    return $wallet;

});

test('cna wallet transactions', function (Wallet $wallet) {


    $command = new WalletTransactionCommand();
    $command->setKey($wallet->id);

    // 收入
    $initBalance = $wallet->balance;

    $initFreeze = $wallet->freeze ?? Money::parse(0, $this->currency);

    $command->amount          = Money::parse(fake()->numberBetween(10000, 20000), $this->currency);
    $command->direction       = AmountDirectionEnum::INCOME;
    $command->transactionType = TransactionTypeEnum::RECHARGE;

    $result = $this->WalletCommandService->transaction($command);
    $this->assertEquals($command->amount->getAmount(), $result->amount->getAmount());
    $wallet = $this->WalletCommandService->repository->find($wallet->id);
    $this->assertEquals(bcadd($initBalance->getAmount(), $command->amount->getAmount(), 0), $wallet->balance->getAmount());


    // 支出
    $initBalance              = $wallet->balance;
    $command->amount          = Money::parse(fake()->numberBetween(100, 200), $this->currency);
    $command->direction       = AmountDirectionEnum::EXPENSE;
    $command->transactionType = TransactionTypeEnum::PAYMENT;
    $result                   = $this->WalletCommandService->transaction($command);
    $wallet                   = $this->WalletCommandService->repository->find($wallet->id);
    $this->assertEquals($command->amount->getAmount(), -$result->amount->getAmount());
    $this->assertEquals(bcsub($initBalance->getAmount(), $command->amount->getAmount(), 0), $wallet->balance->getAmount());


    // 冻结余额
    $initBalance              = $wallet->balance;
    $initFreeze               = $wallet->freeze;

    $freeze                   = Money::parse(fake()->numberBetween(100, 200), $this->currency);
    $command->amount          = clone $freeze;

    $command->direction       = AmountDirectionEnum::FROZEN;
    $command->transactionType = TransactionTypeEnum::FROZEN;
    $result                   = $this->WalletCommandService->transaction($command);
    $wallet                   = $this->WalletCommandService->repository->find($wallet->id);
    $this->assertEquals($command->amount->getAmount(), -$result->amount->getAmount());
    $this->assertEquals(bcsub($initBalance->getAmount(), $command->amount->getAmount(), 0), $wallet->balance->getAmount());
    $this->assertEquals(bcadd($command->amount->getAmount(), $initFreeze->getAmount(), 0), $wallet->freeze->getAmount());


    // 余额解冻
    $initBalance              = $wallet->balance;
    $initFreeze               = $wallet->freeze;

    $command->amount          = clone $freeze;
    $command->direction       = AmountDirectionEnum::UNFROZEN;
    $command->transactionType = TransactionTypeEnum::UNFROZEN;
    $result                   = $this->WalletCommandService->transaction($command);
    $wallet                   = $this->WalletCommandService->repository->find($wallet->id);
    $this->assertEquals($command->amount->getAmount(), $result->amount->getAmount());
    $this->assertEquals(bcadd($initBalance->getAmount(), $command->amount->getAmount(), 0), $wallet->balance->getAmount());
    $this->assertEquals(bcsub($initFreeze->getAmount(), $command->amount->getAmount(), 0), $wallet->freeze->getAmount());


    $wallet = $this->WalletRepository->findByOwnerType(\Illuminate\Support\Facades\Auth::user(), $this->type);
    $this->assertEquals($wallet->balance, $wallet->balance);
    return $wallet;

})->depends('can create a wallet');


test('can create excess payment', function (Wallet $wallet) {

    $command                  = new WalletTransactionCommand();
    $command->setKey( $wallet->id)           ;
    $command->isAllowNegative = true;

    // 支付类型

    $amount                   = Money::parse(fake()->numberBetween(100, 200), $this->currency);
    $initBalance              = $wallet->balance;
    $initFreeze               = $wallet->freeze;
    $command->amount          = clone $amount;
    $command->direction       = AmountDirectionEnum::EXPENSE;
    $command->transactionType = TransactionTypeEnum::PAYMENT;
    $result                   = $this->WalletCommandService->transaction($command);
    $wallet                   = $this->WalletCommandService->repository->find($wallet->id);
    $this->assertEquals($command->amount->getAmount(), -$result->amount->getAmount());
    $this->assertEquals(bcsub($initBalance->getAmount(), $command->amount->getAmount(), 0), $wallet->balance->getAmount());

    // 充值回去
    $initBalance              = $wallet->balance;
    $initFreeze               = $wallet->freeze;
    $command->amount          = clone $amount;
    $command->direction       = AmountDirectionEnum::INCOME;
    $command->transactionType = TransactionTypeEnum::RECHARGE;
    $result                   = $this->WalletCommandService->transaction($command);
    $wallet                   = $this->WalletCommandService->repository->find($wallet->id);
    $this->assertEquals($command->amount->getAmount(), $result->amount->getAmount());
    $this->assertEquals(bcadd($initBalance->getAmount(), $command->amount->getAmount(), 0), $wallet->balance->getAmount());


    $this->expectException(WalletException::class);
    $command->isAllowNegative = false;
    $amount                   = Money::parse(fake()->numberBetween(10000000, 20000000), $this->currency);
    $initBalance              = $wallet->balance;
    $initFreeze               = $wallet->freeze;
    $command->amount          = clone $amount;
    $command->direction       = AmountDirectionEnum::EXPENSE;
    $command->transactionType = TransactionTypeEnum::PAYMENT;
    $result                   = $this->WalletCommandService->transaction($command);


})->depends('cna wallet transactions');
