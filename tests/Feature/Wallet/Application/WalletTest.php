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


    $result  = $this->wallCommandService->create($command);


    $this->assertEquals($command->type, $result->type);
    $this->assertEquals(0, bccomp($result->balance,0,0));


});