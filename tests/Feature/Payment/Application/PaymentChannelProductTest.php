<?php


use RedJasmine\Payment\Application\Services\ChannelCommandService;
use RedJasmine\Payment\Application\Services\ChannelProductCommandService;
use RedJasmine\Payment\Domain\Data\ChannelData;
use RedJasmine\Payment\Domain\Data\ChannelProductData;
use RedJasmine\Payment\Domain\Models\PaymentChannel;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->commandService = app(ChannelCommandService::class);
    $this->repository     = app(ChannelRepositoryInterface::class);
    //

    $this->productCommandService = app(ChannelProductCommandService::class);
    $this->productRepository     = app(ChannelProductRepositoryInterface::class);

    $this->owner = UserData::from([ 'type' => 'user', 'id' => 1 ]);
});


test('can create channel', function () {
    $command          = new ChannelData();
    $command->name    = fake()->word();
    $command->channel = fake()->word();


    $model = $this->commandService->create($command);


    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->channel, $model->channel);

    return $model;
});

test('can create channel product', function (PaymentChannel $channel) {
    $command            = new ChannelProductData();
    $command->channelId = $channel->id;
    $command->code      = fake()->word();
    $command->name      = fake()->word();

    $model = $this->productCommandService->create($command);
    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);
    $this->assertEquals($command->channelId, $model->channel_id);

    return $model;

})->depends('can create channel');
