<?php

use RedJasmine\Payment\Application\Commands\ChannelApp\ChannelAppCreateCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantUpdateCommand;
use RedJasmine\Payment\Application\Services\ChannelAppCommandService;
use RedJasmine\Payment\Application\Services\ChannelCommandService;
use RedJasmine\Payment\Application\Services\ChannelProductCommandService;
use RedJasmine\Payment\Application\Services\MerchantChannelAppPermissionCommandService;
use RedJasmine\Payment\Application\Services\MerchantCommandService;
use RedJasmine\Payment\Application\Services\PlatformCommandService;
use RedJasmine\Payment\Domain\Data\ChannelData;
use RedJasmine\Payment\Domain\Data\ChannelProductData;
use RedJasmine\Payment\Domain\Data\ChannelProductModeData;
use RedJasmine\Payment\Domain\Data\MerchantChannelAppPermissionData;
use RedJasmine\Payment\Domain\Data\PlatformData;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelProductRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\ChannelRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\PlatformRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->paymentMerchantRepository     = app(MerchantRepositoryInterface::class);
    $this->paymentMerchantCommandService = app(MerchantCommandService::class);

    $this->channelCommandService = app(ChannelCommandService::class);
    $this->channelRepository     = app(ChannelRepositoryInterface::class);


    $this->channelAppCommandService = app(ChannelAppCommandService::class);
    $this->channelAppRepository     = app(ChannelAppRepositoryInterface::class);
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

    //
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
            $model = $this->channelRepository->findByCode($command->code);
        } catch (Throwable $throwable) {
            $model = $this->channelCommandService->create($command);
        }


        $this->assertEquals($command->code, $model->code);
    }

})->depends('can create a platform');
// 创建渠道产品
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

// 创建渠道应用
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


        $model = $this->channelAppCommandService->create($command);

        $apps[] = $model = $this->channelAppRepository->find($model->id);

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


test('can create merchant', function () {

    $command            = new MerchantCreateCommand();
    $command->owner     = UserData::from([ 'type' => 'user', 'id' => 1 ]);
    $command->name      = 'XXX有限公司';
    $command->shortName = '测试';
    $merchant           = $this->paymentMerchantCommandService->create($command);
    $this->assertEquals($command->name, $merchant->name, '商户名称');

    return $merchant;
});

// 授权商户应用
test('can authorize channel app', function ($apps, $merchant) {


    /**
     * @var Merchant $merchant
     */
    foreach ($apps as $app) {
        $command               = new MerchantChannelAppPermissionData();
        $command->channelAppId = $app->id;
        $command->merchantId   = $merchant->id;
        $service               = app(MerchantChannelAppPermissionCommandService::class);
        $service->authorize($command);


    }
    $merchant = app(MerchantRepositoryInterface::class)->find($merchant->id);

    $this->assertEquals(count($apps), $merchant->channelApps->count());


    /**
     * @var Merchant $merchant
     */
    foreach ($apps as $app) {
        $command               = new MerchantChannelAppPermissionData();
        $command->channelAppId = $app->id;
        $command->merchantId   = $merchant->id;
        $command->status       = PermissionStatusEnum::DISABLED;
        $service               = app(MerchantChannelAppPermissionCommandService::class);
        $service->authorize($command);


    }
    $merchant = app(MerchantRepositoryInterface::class)->find($merchant->id);

    $this->assertEquals(0, $merchant->channelApps->count());


})->depends('create payment channel apps', 'can create merchant');

test('can set status', function (Merchant $merchant) {
    $command         = new MerchantSetStatusCommand();
    $command->id     = $merchant->id;
    $command->status = MerchantStatusEnum::DISABLED;
    $this->paymentMerchantCommandService->setStatus($command);

    $merchant = $this->paymentMerchantRepository->find($command->id);

    $this->assertEquals($command->status->value, $merchant->status->value, '商户状态');
    return $merchant;
})->depends('can create merchant');


test('can update merchant', function (Merchant $merchant) {

    $command     = new MerchantUpdateCommand();
    $command->id = $merchant->id;

    $command->owner = UserData::from([ 'type' => 'user', 'id' => 1 ]);

    $command->name      = 'XXXX有限公司';
    $command->shortName = '测试';


    $merchant = $this->paymentMerchantCommandService->update($command);

    $this->assertEquals($command->name, $merchant->name, '商户名称');

    return $merchant;
})->depends('can create merchant');

