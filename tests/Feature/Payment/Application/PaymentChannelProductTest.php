<?php


use RedJasmine\Payment\Application\Services\ChannelCommandService;
use RedJasmine\Payment\Application\Services\ChannelProductCommandService;
use RedJasmine\Payment\Application\Services\PlatformCommandService;
use RedJasmine\Payment\Domain\Data\ChannelData;
use RedJasmine\Payment\Domain\Data\ChannelProductData;
use RedJasmine\Payment\Domain\Data\ChannelProductModeData;
use RedJasmine\Payment\Domain\Data\PlatformData;
use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\PaymentMethodEnum;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\PlatformRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->commandService = app(ChannelCommandService::class);
    $this->repository     = app(ChannelRepositoryInterface::class);
    //

    $this->productCommandService = app(ChannelProductCommandService::class);
    $this->productRepository     = app(ChannelProductRepositoryInterface::class);

    $this->owner = UserData::from([ 'type' => 'user', 'id' => 1 ]);


    $this->platformRepository     = app(PlatformRepositoryInterface::class);
    $this->platformCommandService = app(PlatformCommandService::class);


});

test('init', function () {
    $command = new PlatformData();

    $command->code    = 'wechat';
    $command->name    = '微信';
    $command->icon    = fake()->imageUrl(40, 40);
    $command->remarks = fake()->text();


    try {
        $this->platformRepository->findByCode($command->code);
    } catch (Throwable $throwable) {
        $this->platformCommandService->create($command);
    }

    $wechat = $this->platformRepository->findByCode($command->code);


    $this->assertEquals($command->code, $wechat->code);



    $command->code = 'alipay';
    $command->name = '支付宝';

    try {
        $this->platformRepository->findByCode($command->code);
    } catch (Throwable $throwable) {
        $this->platformCommandService->create($command);
    }

    $alipay = $this->platformRepository->findByCode($command->code);

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
    $command->code        = fake()->word();
    $command->name        = fake()->word();
    $command->rate        = 0.6;
    $command->modes       = [
        ChannelProductModeData::from([ 'methodCode' => PaymentMethodEnum::WEB->value, 'platformCode' => 'alipay' ]),
        ChannelProductModeData::from([ 'methodCode' => PaymentMethodEnum::JSAPI->value, 'platformCode' => 'wechat' ]),
    ];


    $model = $this->productCommandService->create($command);


    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);
    $this->assertEquals($command->channelCode, $model->channel_code);
    $this->assertEquals($command->rate, $model->rate);
    $this->assertEquals($command->channelCode, $model->channel->code);

    $this->assertEquals(true, $model->channel->products->where('channel_code', $command->channelCode)->count() >= 1);


    return $model;

})->depends('can create channel');


test('can update a channel product', function (Channel $channel, ChannelProduct $channelProduct) {

    $command              = new ChannelProductData();
    $command->channelCode = $channel->code;
    $command->code        = fake()->word();
    $command->name        = fake()->word();
    $command->rate        = 0.6;
    $command->modes       = [
        ChannelProductModeData::from([ 'methodCode' => PaymentMethodEnum::WEB->value,
                                       'platformCode' => 'alipay' ]),
        ChannelProductModeData::from([ 'methodCode'   => PaymentMethodEnum::JSAPI->value,
                                       'platformCode' => 'wechat',
                                       'status' => ModeStatusEnum::DISABLED ]),
        ChannelProductModeData::from([ 'methodCode'   => PaymentMethodEnum::WEB->value,
                                       'platformCode' => 'wechat' ]),
    ];

    $command->id = $channelProduct->id;

    $this->productCommandService->update($command);

    $model = $this->productRepository->find($command->id);
    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->code, $model->code);
    $this->assertEquals($command->channelCode, $model->channel_code);
    $this->assertEquals($command->rate, $model->rate);
    $this->assertEquals($command->channelCode, $model->channel->code);

    $this->assertEquals(count($command->modes), $model->modes->count());
    $this->assertEquals(1, $model->modes->where('status', ModeStatusEnum::DISABLED)->count());

    return $model;

})->depends('can create channel', 'can create channel product');
