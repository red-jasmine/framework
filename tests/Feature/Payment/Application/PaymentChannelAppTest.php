<?php

use RedJasmine\Payment\Application\Services\Channel\ChannelCommandService;
use RedJasmine\Payment\Application\Services\ChannelApp\ChannelAppCommandService;
use RedJasmine\Payment\Application\Services\ChannelApp\Commands\ChannelAppCreateCommand;
use RedJasmine\Payment\Application\Services\ChannelApp\Commands\ChannelAppUpdateCommand;
use RedJasmine\Payment\Application\Services\ChannelMerchant\ChannelMerchantCommandService;
use RedJasmine\Payment\Application\Services\ChannelProduct\ChannelProductCommandService;
use RedJasmine\Payment\Application\Services\Method\MethodCommandService;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelMerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;
use RedJasmine\Tests\Feature\Payment\Fixtures\BaseDataFixtures;

beforeEach(function () {

    BaseDataFixtures::init($this);

    $this->commandService = app(ChannelAppCommandService::class);
    $this->repository     = app(ChannelAppRepositoryInterface::class);


    $this->ChannelCommandService = app(ChannelCommandService::class);
    $this->ChannelRepository     = app(ChannelRepositoryInterface::class);


    $this->methodRepository     = app(MethodRepositoryInterface::class);
    $this->methodCommandService = app(MethodCommandService::class);


    $this->productCommandService = app(ChannelProductCommandService::class);
    $this->productRepository     = app(ChannelProductRepositoryInterface::class);


});


test('create payment channel apps', function () {


    $command = new ChannelAppCreateCommand();


    $apps = [];

    /**
     * @var $channel Channel
     */
    foreach ($this->channels as $channel) {
        $command->owner       = $this->owner;
        $command->channelCode = $channel->code;

        $command->channelAppId         = fake()->numerify('channel-app-id-########');
        $command->channelMerchantId    = fake()->numerify('channel-merchant-id-########');
        $command->channelAppPublicKey  = fake()->text(3000);//
        $command->channelPublicKey     = fake()->text(3000);//
        $command->channelAppPrivateKey = fake()->text(3000);//
        $command->appName              = fake()->word();//
        $command->merchantName         = fake()->word();//

        // 开通所有产品

        foreach ($this->channelProducts as $product) {
            if ($product->channel_code === $channel->code) {
                $command->products[] = $product->id;
            }

        }

        $model = $this->commandService->create($command);

        $apps[] = $model = $this->repository->find($model->id);

        $this->assertEquals($command->channelMerchantId, $model->channel_merchant_id);
        $this->assertEquals($command->merchantName, $model->merchant_name);
        $this->assertEquals($command->channelAppId, $model->channel_app_id);
        $this->assertEquals($command->channelAppPublicKey, $model->channel_app_public_key);
        $this->assertEquals($command->channelPublicKey, $model->channel_public_key);
        $this->assertEquals($command->channelAppPrivateKey, $model->channel_app_private_key);
        $this->assertEquals($command->appName, $model->app_name);
        $this->assertEquals(true, $model->products->count() > 0);

    }

    return $apps;


});


test('update payment channel apps', function ($channelApps) {


    /**
     * @var $channelApp ChannelApp
     */
    foreach ($channelApps as $channelApp) {
        $command                       = new ChannelAppUpdateCommand();
        $command->id                   = $channelApp->id;
        $command->channelCode          = $channelApp->channel_code;
        $command->owner                = $this->owner;
        $command->channelAppId         = fake()->numerify('channel-app-id-########');
        $command->channelMerchantId    = fake()->numerify('channel-merchant-id-########');
        $command->channelAppPublicKey  = fake()->text(3000);//
        $command->channelPublicKey     = fake()->text(3000);//
        $command->channelAppPrivateKey = fake()->text(3000);//
        $command->appName              = fake()->word();//
        $command->merchantName         = fake()->word();//
        $command->products             = [];

        foreach ($this->channelProducts as $product) {
            if ($product->channel_code === $channelApp->channel_code) {
                $command->products[] = $product->id;
                break;
            }

        }

        $model = $this->commandService->update($command);


        $model = $this->repository->find($model->id);

        $this->assertEquals($command->channelMerchantId, $model->channel_merchant_id);
        $this->assertEquals($command->merchantName, $model->merchant_name);
        $this->assertEquals($command->channelAppId, $model->channel_app_id);
        $this->assertEquals($command->channelAppPublicKey, $model->channel_app_public_key);
        $this->assertEquals($command->channelPublicKey, $model->channel_public_key);
        $this->assertEquals($command->channelAppPrivateKey, $model->channel_app_private_key);
        $this->assertEquals($command->appName, $model->app_name);
        $this->assertEquals(count($command->products), $model->products->count());

    }


})->depends('create payment channel apps');



