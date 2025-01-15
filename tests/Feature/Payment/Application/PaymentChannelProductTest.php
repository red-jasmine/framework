<?php


use RedJasmine\Payment\Application\Services\Channel\ChannelCommandService;
use RedJasmine\Payment\Application\Services\ChannelProduct\ChannelProductCommandService;
use RedJasmine\Payment\Application\Services\Method\MethodCommandService;
use RedJasmine\Payment\Domain\Data\ChannelData;
use RedJasmine\Payment\Domain\Data\ChannelProductData;
use RedJasmine\Payment\Domain\Data\ChannelProductModeData;
use RedJasmine\Payment\Domain\Data\MethodData;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;

beforeEach(function () {

    $this->commandService = app(ChannelCommandService::class);
    $this->repository     = app(ChannelRepositoryInterface::class);
    //

    $this->productCommandService = app(ChannelProductCommandService::class);
    $this->productRepository     = app(ChannelProductRepositoryInterface::class);


    $this->methodRepository     = app(MethodRepositoryInterface::class);
    $this->methodCommandService = app(MethodCommandService::class);


});

test('init', function () {
    $command = new MethodData();

    $command->code    = 'wechat';
    $command->name    = '微信';
    $command->icon    = fake()->imageUrl(40, 40);
    $command->remarks = fake()->text();


    try {
        $this->methodRepository->findByCode($command->code);
    } catch (Throwable $throwable) {
        $this->methodCommandService->create($command);
    }

    $wechat = $this->methodRepository->findByCode($command->code);


    $this->assertEquals($command->code, $wechat->code);


    $command->code = 'alipay';
    $command->name = '支付宝';

    try {
        $this->methodRepository->findByCode($command->code);
    } catch (Throwable $throwable) {
        $this->methodCommandService->create($command);
    }

    $alipay = $this->methodRepository->findByCode($command->code);

    $this->assertEquals($command->code, $alipay->code);

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

test('can create channel product', function (Channel $channel) {


    $command              = new ChannelProductData();
    $command->channelCode = $channel->code;
    $command->type        = ChannelProductTypeEnum::PAYMENT;
    $command->code        = fake()->word();
    $command->name        = fake()->word();

    $command->modes = [
        ChannelProductModeData::from([ 'sceneCode' => SceneEnum::WEB->value, 'methodCode' => 'alipay' ]),
        ChannelProductModeData::from([ 'sceneCode' => SceneEnum::JSAPI->value, 'methodCode' => 'wechat' ]),
    ];


    $model = $this->productCommandService->create($command);


    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);
    $this->assertEquals($command->channelCode, $model->channel_code);

    $this->assertEquals($command->channelCode, $model->channel->code);

    $this->assertEquals(true, $model->channel->products->where('channel_code', $command->channelCode)->count() >= 1);


    return $model;

})->depends('can create channel');


test('can update a channel product', function (Channel $channel, ChannelProduct $channelProduct) {

    $command              = new ChannelProductData();
    $command->channelCode = $channel->code;
    $command->code        = fake()->word();
    $command->name        = fake()->word();

    $command->modes = [
        ChannelProductModeData::from([
                                         'sceneCode'  => SceneEnum::WEB->value,
                                         'methodCode' => 'alipay'
                                     ]),
        ChannelProductModeData::from([
                                         'sceneCode'  => SceneEnum::JSAPI->value,
                                         'methodCode' => 'wechat',
                                         'status'     => ModeStatusEnum::DISABLE
                                     ]),
        ChannelProductModeData::from([
                                         'sceneCode'  => SceneEnum::WEB->value,
                                         'methodCode' => 'wechat'
                                     ]),
    ];

    $command->id = $channelProduct->id;

    $this->productCommandService->update($command);

    $model = $this->productRepository->find($command->id);
    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);
    $this->assertEquals($command->channelCode, $model->channel_code);

    $this->assertEquals($command->channelCode, $model->channel->code);

    $this->assertEquals(count($command->modes), $model->modes->count());
    $this->assertEquals(1, $model->modes->where('status', ModeStatusEnum::DISABLE)->count());

    return $model;

})->depends('can create channel', 'can create channel product');
