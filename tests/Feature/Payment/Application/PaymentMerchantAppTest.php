<?php

use RedJasmine\Payment\Application\Services\ChannelApp\ChannelAppCommandService;
use RedJasmine\Payment\Application\Services\Merchant\MerchantCommandService;
use RedJasmine\Payment\Application\Services\MerchantApp\Commands\MerchantAppCreateCommand;
use RedJasmine\Payment\Application\Services\MerchantApp\Commands\MerchantAppUpdateCommand;
use RedJasmine\Payment\Application\Services\MerchantApp\MerchantAppApplicationService;
use RedJasmine\Payment\Domain\Data\MerchantChannelAppPermissionData;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Tests\Feature\Payment\Fixtures\BaseDataFixtures;

beforeEach(function () {

    BaseDataFixtures::init($this);
    $this->paymentMerchantRepository     = app(MerchantRepositoryInterface::class);
    $this->paymentMerchantCommandService = app(MerchantCommandService::class);

    $this->paymentMerchantAppRepository     = app(MerchantAppRepositoryInterface::class);
    $this->paymentMerchantAppCommandService = app(MerchantAppApplicationService::class);
    //
});


test('can create a merchant app', function () {
    $merchant = $this->merchant;

    $command             = new MerchantAppCreateCommand();
    $command->name       = fake()->company;
    $command->merchantId = $merchant->id;

    $model = $this->paymentMerchantAppCommandService->create($command);

    $this->assertEquals($command->name, $model->name, '商户应用名称');

    $this->assertNotEmpty($model->system_public_key);
    $this->assertNotEmpty($model->system_private_key);


    return $model;

});


// 授权商户应用 TODO
test('can authorize channel app', function (MerchantApp $merchantApp) {


    foreach ($this->channelApps as $app) {
        $command                = new MerchantChannelAppPermissionData();
        $command->channelAppId  = $app->id;
        $command->merchantAppId = $merchantApp->id;

        $service = app(ChannelAppCommandService::class);
        $service->authorize($command);


    }
    $merchantApp = app(MerchantAppRepositoryInterface::class)->find($merchantApp->id);

    $this->assertEquals(count($this->channelApps), $merchantApp->channelApps->count());


    foreach ($this->channelApps as $app) {
        $command                = new MerchantChannelAppPermissionData();
        $command->channelAppId  = $app->id;
        $command->merchantAppId = $merchantApp->id;
        $command->status        = PermissionStatusEnum::DISABLE;
        $service                = app(ChannelAppCommandService::class);
        $service->authorize($command);


    }
    $merchantApp = app(MerchantAppRepositoryInterface::class)->find($merchantApp->id);

    $this->assertEquals(0, $merchantApp->channelApps->count());


})->depends('can create a merchant app');


test('can update a merchant app', function (MerchantApp $merchantApp) {


    $command             = new MerchantAppUpdateCommand();
    $command->setKey($merchantApp->id)     ;
    $command->name       = fake()->company;
    $command->merchantId = $merchantApp->merchant_id;
    $command->status     = MerchantAppStatusEnum::DISABLE;
    $model               = $this->paymentMerchantAppCommandService->update($command);

    $this->assertEquals($command->name, $model->name, '商户应用名称');
    $this->assertEquals($command->status->value, $model->status->value, ' 商户应用状态');

})->depends('can create a merchant app');


