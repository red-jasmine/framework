<?php


use RedJasmine\Payment\Application\Services\Channel\ChannelCommandService;
use RedJasmine\Payment\Domain\Data\ChannelData;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->commandService = app(ChannelCommandService::class);
    $this->repository     = app(ChannelRepositoryInterface::class);
    //

});


test('can create channel', function () {
    $command       = new ChannelData();
    $command->name = fake()->password();
    $command->code = fake()->password();


    $model = $this->commandService->create($command);


    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);

});
