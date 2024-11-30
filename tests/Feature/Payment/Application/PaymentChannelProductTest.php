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
    $command       = new ChannelData();
    $command->name = fake()->word();
    $command->code = fake()->word();


    $model = $this->commandService->create($command);


    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);

    return $model;
});

test('can create channel product', function (PaymentChannel $channel) {
    $command              = new ChannelProductData();
    $command->channelCode = $channel->code;
    $command->code        = fake()->word();
    $command->name        = fake()->word();
    $command->rate        = 0.6;

    $model = $this->productCommandService->create($command);
    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);
    $this->assertEquals($command->channelCode, $model->channel_code);
    $this->assertEquals($command->rate, $model->rate);


    $this->assertEquals($command->channelCode, $model->channel->code);


    $this->assertEquals(true, $model->channel->products->where('channel_code', $command->channelCode)->count() >= 1);


    return $model;

})->depends('can create channel');
