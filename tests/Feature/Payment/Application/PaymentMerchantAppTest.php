<?php

use RedJasmine\Payment\Application\Services\Merchant\Commands\MerchantCreateCommand;
use RedJasmine\Payment\Application\Services\Merchant\MerchantCommandService;
use RedJasmine\Payment\Application\Services\MerchantApp\Commands\MerchantAppCreateCommand;
use RedJasmine\Payment\Application\Services\MerchantApp\Commands\MerchantAppUpdateCommand;
use RedJasmine\Payment\Application\Services\MerchantApp\MerchantAppCommandService;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->paymentMerchantRepository     = app(MerchantRepositoryInterface::class);
    $this->paymentMerchantCommandService = app(MerchantCommandService::class);

    $this->paymentMerchantAppRepository     = app(MerchantAppRepositoryInterface::class);
    $this->paymentMerchantAppCommandService = app(MerchantAppCommandService::class);
    //
});


test('can create merchant', function () {

    $command = new MerchantCreateCommand();


    $command->owner = UserData::from([ 'type' => 'user', 'id' => 1 ]);

    $command->name      = 'XXX有限公司';
    $command->shortName = '测试';


    $merchant = $this->paymentMerchantCommandService->create($command);


    $this->assertEquals($command->name, $merchant->name, '商户名称');

    return $merchant;
});

test('can create a merchant app', function (Merchant $merchant) {


    $command             = new MerchantAppCreateCommand();
    $command->name       = fake()->company;
    $command->merchantId = $merchant->id;

    $model = $this->paymentMerchantAppCommandService->create($command);

    $this->assertEquals($command->name, $model->name, '商户应用名称');

    $this->assertNotEmpty($model->system_public_key);
    $this->assertNotEmpty($model->system_private_key);


    return $model;

})->depends('can create merchant');


test('can update a merchant app', function (MerchantApp $merchantApp) {


    $command             = new MerchantAppUpdateCommand();
    $command->id         = $merchantApp->id;
    $command->name       = fake()->company;
    $command->merchantId = $merchantApp->merchant_id;
    $command->status     = MerchantAppStatusEnum::DISABLE;
    $model               = $this->paymentMerchantAppCommandService->update($command);

    $this->assertEquals($command->name, $model->name, '商户应用名称');
    $this->assertEquals($command->status->value, $model->status->value, ' 商户应用状态');

})->depends('can create a merchant app');


