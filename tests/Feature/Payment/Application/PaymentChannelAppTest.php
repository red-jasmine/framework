<?php

use RedJasmine\Payment\Application\Commands\ChannelApp\ChannelAppCreateCommand;
use RedJasmine\Payment\Application\Commands\ChannelApp\ChannelAppUpdateCommand;
use RedJasmine\Payment\Application\Services\ChannelAppCommandService;
use RedJasmine\Payment\Application\Services\ChannelCommandService;
use RedJasmine\Payment\Application\Services\ChannelProductCommandService;
use RedJasmine\Payment\Application\Services\PlatformCommandService;
use RedJasmine\Payment\Domain\Data\ChannelData;
use RedJasmine\Payment\Domain\Data\ChannelProductData;
use RedJasmine\Payment\Domain\Data\ChannelProductModeData;
use RedJasmine\Payment\Domain\Data\PlatformData;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\PlatformRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->commandService = app(ChannelAppCommandService::class);
    $this->repository     = app(ChannelAppRepositoryInterface::class);
    //


    $this->ChannelCommandService = app(ChannelCommandService::class);
    $this->ChannelRepository     = app(ChannelRepositoryInterface::class);


    $this->platformRepository     = app(PlatformRepositoryInterface::class);
    $this->platformCommandService = app(PlatformCommandService::class);


    $this->productCommandService = app(ChannelProductCommandService::class);
    $this->productRepository     = app(ChannelProductRepositoryInterface::class);


    $this->owner = UserData::from([ 'type' => 'user', 'id' => 1 ]);


    $this->platformData = [
        [ 'code' => 'alipay', 'name' => '支付宝' ],
        [ 'code' => 'wechat', 'name' => '微信' ],
    ];

    $this->channelData = [
        [ 'code' => 'alipay', 'name' => '支付宝' ],
        [ 'code' => 'wechat', 'name' => '微信支付' ],
    ];


    $this->productsData = [
        [
            'code'   => 'web',
            'name'   => '电脑网站支付',
            'models' => [
                [
                    'method_code' => 'web',

                ]
            ],
        ],
        [
            'code'   => 'wap',
            'name'   => '手机网站支付',
            'models' => [
                [
                    'method_code' => 'wap',

                ]
            ],
        ]

    ];


});

// 创建平台
test('can create a platform', function () {
    $command = new PlatformData();

    $command->icon    = fake()->imageUrl(40, 40);
    $command->remarks = fake()->text();
    foreach ($this->platformData as $platform) {
        $command->code = $platform['code'];
        $command->name = $platform['name'];
        try {

            $model = $this->platformRepository->findByCode($command->code);
        } catch (Throwable $throwable) {
            $model = $this->platformCommandService->create($command);
        }


        $this->assertEquals($command->code, $model->code);
    }


});

// 创建渠道
test('can create channel', function () {
    $command = new ChannelData();

    foreach ($this->channelData as $channel) {
        $command->code = $channel['code'];
        $command->name = $channel['name'];

        try {
            $model = $this->ChannelRepository->findByCode($command->code);
        } catch (Throwable $throwable) {
            $model = $this->ChannelCommandService->create($command);
        }


        $this->assertEquals($command->code, $model->code);
    }

});


test('can create channel product', function () {
    $command  = new ChannelProductData();
    $products = [];
    foreach ($this->channelData as $channelData) {

        $command->channelCode = $channelData['code'];
        $command->rate        = 0.6;

        foreach ($this->productsData as $productData) {

            $command->code = $productData['code'];
            $command->name = $productData['name'];
            $models        = [];
            foreach ($productData['models'] as $modelData) {
                $channelProductModeData = new ChannelProductModeData();

                $channelProductModeData->methodCode   = $modelData['method_code'];
                $channelProductModeData->platformCode = $command->channelCode;
                $models[]                             = $channelProductModeData;
            }
            $command->modes = $models;
            try {
                $model                             = $this->productRepository->findByCode($command->channelCode, $command->code);
                $command->id                       = $model->id;
                $products[$command->channelCode][] = $model;
                $this->productCommandService->update($command);
            } catch (Throwable $throwable) {
                $products[$command->channelCode][] = $model = $this->productCommandService->create($command);
            }


            $this->assertEquals($command->name, $model->name);
            $this->assertEquals($command->code, $model->code);
            $this->assertEquals($command->channelCode, $model->channel_code);
            $this->assertEquals($command->rate, $model->rate);
            $this->assertEquals($command->channelCode, $model->channel->code);
        }

    }

    return $products;

})->depends('can create channel');


test('create payment channel apps', function ($products) {


    $command        = new ChannelAppCreateCommand();
    $command->owner = $this->owner;
    $apps           = [];
    foreach ($this->channelData as $channelData) {
        $channel                       = $this->ChannelRepository->findByCode($channelData['code']);
        $command->channelId            = $channel->id;
        $command->channelMerchantId    = fake()->numerify('channel-merchant-id-########');
        $command->channelAppId         = fake()->numerify('channel-app-id-########');
        $command->channelAppPublicKey  = fake()->text(3000);//
        $command->channelPublicKey     = fake()->text(3000);//
        $command->channelAppPrivateKey = fake()->text(3000);//
        $command->merchantName         = fake()->word();//
        $command->appName              = fake()->word();//

        // 开通的产品

        foreach ($products[$channel->code] ?? [] as $product) {

            $command->products[] = $product->id;
        }


        $model = $this->commandService->create($command);

        $apps[] = $model = $this->repository->find($model->id);

        $this->assertEquals($command->channelId, $model->channel_id);
        $this->assertEquals($command->channelMerchantId, $model->channel_merchant_id);
        $this->assertEquals($command->channelAppId, $model->channel_app_id);
        $this->assertEquals($command->channelAppPublicKey, $model->channel_app_public_key);
        $this->assertEquals($command->channelPublicKey, $model->channel_public_key);
        $this->assertEquals($command->channelAppPrivateKey, $model->channel_app_private_key);
        $this->assertEquals($command->merchantName, $model->merchant_name);
        $this->assertEquals($command->appName, $model->app_name);
        $this->assertEquals(true, $model->products->count() > 0);

    }

    return $apps;


})->depends('can create channel product');


test('update payment channel apps', function ($products, $channelApps) {


    /**
     * @var $channelApp ChannelApp
     */
    foreach ($channelApps as $channelApp) {
        $command            = new ChannelAppUpdateCommand();
        $command->id        = $channelApp->id;
        $command->channelId = $channelApp->channel->id;


        $command->channelMerchantId    = fake()->numerify('channel-merchant-id-########');
        $command->channelAppId         = fake()->numerify('channel-app-id-########');
        $command->channelAppPublicKey  = fake()->text(3000);//
        $command->channelPublicKey     = fake()->text(3000);//
        $command->channelAppPrivateKey = fake()->text(3000);//
        $command->merchantName         = fake()->word();//
        $command->appName              = fake()->word();//

        foreach ($products[$channelApp->channel->code] ?? [] as $product) {

            $command->products[] = $product->id;
            break;
        }


        $model = $this->commandService->update($command);


        $model = $this->repository->find($model->id);

        $this->assertEquals($command->channelId, $model->channel_id);
        $this->assertEquals($command->channelMerchantId, $model->channel_merchant_id);
        $this->assertEquals($command->channelAppId, $model->channel_app_id);
        $this->assertEquals($command->channelAppPublicKey, $model->channel_app_public_key);
        $this->assertEquals($command->channelPublicKey, $model->channel_public_key);
        $this->assertEquals($command->channelAppPrivateKey, $model->channel_app_private_key);
        $this->assertEquals($command->merchantName, $model->merchant_name);
        $this->assertEquals($command->appName, $model->app_name);
        $this->assertEquals(1, $model->products->count());

    }


})->depends('can create channel product', 'create payment channel apps');


// TEST 给商户授权 TODO
