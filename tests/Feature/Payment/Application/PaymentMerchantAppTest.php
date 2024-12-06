<?php

use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Application\Commands\MerchantApp\MerchantAppCreateCommand;
use RedJasmine\Payment\Application\Commands\MerchantApp\MerchantAppUpdateCommand;
use RedJasmine\Payment\Application\Services\MerchantAppCommandService;
use RedJasmine\Payment\Application\Services\MerchantCommandService;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Models\PaymentMerchantApp;
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

    return $model;

})->depends('can create merchant');


test('can update a merchant app', function (PaymentMerchantApp $merchantApp) {


    $command             = new MerchantAppUpdateCommand();
    $command->id         = $merchantApp->id;
    $command->name       = fake()->company;
    $command->merchantId = $merchantApp->merchant_id;
    $command->status     = MerchantAppStatusEnum::DISABLED;
    $model               = $this->paymentMerchantAppCommandService->update($command);

    $this->assertEquals($command->name, $model->name, '商户应用名称');
    $this->assertEquals($command->status->value, $model->status->value, ' 商户应用状态');

})->depends('can create a merchant app');


