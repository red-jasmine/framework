<?php

use RedJasmine\Payment\Application\Commands\Merchant\MerchantCreateCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantSetStatusCommand;
use RedJasmine\Payment\Application\Commands\Merchant\MerchantUpdateCommand;
use RedJasmine\Payment\Application\Services\MerchantCommandService;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Data\UserData;

beforeEach(function () {

    $this->paymentMerchantRepository     = app(MerchantRepositoryInterface::class);
    $this->paymentMerchantCommandService = app(MerchantCommandService::class);


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

