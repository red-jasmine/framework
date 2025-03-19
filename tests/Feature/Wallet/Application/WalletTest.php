<?php

use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use RedJasmine\Wallet\Application\Services\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\WalletCommandService;

beforeEach(function () {
    $this->WalletCommandService = app(WalletCommandService::class);
    $this->WalletRepository     = app(\RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface::class);
});
test('can create a wallet', function () {

    $command = new WalletCreateCommand();

    $command->owner = \Illuminate\Support\Facades\Auth::user();

    $command->type = 'point';

    $command->currency = 'DFC';


    if ($wallet = $this->WalletRepository->findByOwnerType($command->owner, $command->type)) {
        return $wallet;
    }

    $wallet = $result = $this->WalletCommandService->create($command);
    $this->assertEquals($command->type, $result->type);
    $this->assertEquals(0, bccomp($result->balance, 0, 0));


    return $wallet;

});

test('cna wallet recharge', function (\RedJasmine\Wallet\Domain\Models\Wallet $wallet) {

    $command                  = new \RedJasmine\Wallet\Application\Services\Commands\WalletTransactionCommand();
    $command->id              = $wallet->id;
    $command->amount          = new Amount(100, 'DFC');

    $command->transactionType = \RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum::RECHARGE;


    $result = $this->WalletCommandService->transaction($command);

    dd($result);


})->depends('can create a wallet');
