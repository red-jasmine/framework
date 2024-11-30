<?php

use RedJasmine\Payment\Application\Commands\ChannelApp\ChannelAppCreateCommand;
use RedJasmine\Payment\Application\Commands\ChannelApp\ChannelAppUpdateCommand;
use RedJasmine\Payment\Application\Services\ChannelAppCommandService;
use RedJasmine\Payment\Domain\Models\PaymentChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->commandService = app(ChannelAppCommandService::class);
    $this->repository     = app(ChannelAppRepositoryInterface::class);
    //

    $this->owner = UserData::from([ 'type' => 'user', 'id' => 1 ]);
});

test('create payment channel app', function () {


    $command = new ChannelAppCreateCommand();

    $command->owner       = $this->owner;
    $command->channelCode = 'alipay';

    $command->channelMerchantId    = fake()->numerify('channel-merchant-id-########');
    $command->channelAppId         = fake()->numerify('channel-app-id-########');
    $command->channelAppPublicKey  = fake()->text(3000);//
    $command->channelPublicKey     = fake()->text(3000);//
    $command->channelAppPrivateKey = fake()->text(3000);//

    $model = $this->commandService->create($command);

    $model = $this->repository->find($model->id);

    $this->assertEquals($command->channelCode, $model->channel_code);
    $this->assertEquals($command->channelMerchantId, $model->channel_merchant_id);
    $this->assertEquals($command->channelAppId, $model->channel_app_id);
    $this->assertEquals($command->channelAppPublicKey, $model->channel_app_public_key);
    $this->assertEquals($command->channelPublicKey, $model->channel_public_key);
    $this->assertEquals($command->channelAppPrivateKey, $model->channel_app_private_key);


    return $model;
});

test('update payment channel app', function (PaymentChannelApp $channelApp) {

    $command                       = new ChannelAppUpdateCommand();
    $command->id                   = $channelApp->id;
    $command->channelCode          = 'wechatpay';
    $command->channelMerchantId    = fake()->numerify('channel-merchant-id-########');
    $command->channelAppId         = fake()->numerify('channel-app-id-########');
    $command->channelAppPublicKey  = fake()->text(3000);//
    $command->channelPublicKey     = fake()->text(3000);//
    $command->channelAppPrivateKey = fake()->text(3000);//

    $model = $this->commandService->update($command);

    $model = $this->repository->find($model->id);

    $this->assertEquals($command->channelCode, $model->channel_code);
    $this->assertEquals($command->channelMerchantId, $model->channel_merchant_id);
    $this->assertEquals($command->channelAppId, $model->channel_app_id);
    $this->assertEquals($command->channelAppPublicKey, $model->channel_app_public_key);
    $this->assertEquals($command->channelPublicKey, $model->channel_public_key);
    $this->assertEquals($command->channelAppPrivateKey, $model->channel_app_private_key);

})->depends('create payment channel app');
