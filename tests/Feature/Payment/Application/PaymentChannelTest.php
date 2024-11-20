<?php


use RedJasmine\Payment\Application\Services\ChannelCommandService;
use RedJasmine\Payment\Domain\Data\ChannelData;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->commandService = app(ChannelCommandService::class);
    $this->repository     = app(ChannelRepositoryInterface::class);
    //

    $this->owner = UserData::from([ 'type' => 'user', 'id' => 1 ]);
});


test('can create channel', function () {
    $command          = new ChannelData();
    $command->name    = fake()->word();
    $command->channel = fake()->word();


    $model = $this->commandService->create($command);


    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->channel, $model->channel);

});
