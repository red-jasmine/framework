<?php


use RedJasmine\Wallet\Application\Services\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\WalletCommandService;

beforeEach(function () {

    $this->wallCommandService = app(WalletCommandService::class);
});
test('can create a wallet', function () {

    $command = new WalletCreateCommand();

    $command->owner = \Illuminate\Support\Facades\Auth::user();

    $command->type = 'points';


    $this->wallCommandService->create($command);


});