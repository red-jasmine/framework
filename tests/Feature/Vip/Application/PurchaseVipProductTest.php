<?php

use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Vip\Application\Services\Commands\UserPurchaseVipCommand;
use RedJasmine\Vip\Application\Services\UserVipCommandService;
use RedJasmine\Vip\Application\Services\VipCommandService;
use RedJasmine\Vip\Application\Services\VipProductCommandService;
use RedJasmine\Vip\Application\Services\VipPurchaseCommandService;
use RedJasmine\Vip\Domain\Data\VipData;
use RedJasmine\Vip\Domain\Data\VipProductData;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;

beforeEach(function () {

    $this->VipCommandService = app(VipCommandService::class);

    $this->UserVipCommandService = app(UserVipCommandService::class);
    $this->VipReadRepository     = app(VipReadRepositoryInterface::class);
    $this->appId                 = 'test';
    $this->type                  = 'vip';


    $this->VipProductCommandService  = app(VipProductCommandService::class);
    $this->VipProductReadRepository  = app(VipProductReadRepositoryInterface::class);
    $this->VipProductRepository      = app(VipProductRepositoryInterface::class);
    $this->VipPurchaseCommandService = app(VipPurchaseCommandService::class);

});

test('create a vip', function () {

    $command = VipData::from([
        'appId' => $this->appId,
        'type'  => $this->type,
        'name'  => fake()->title(),

    ]);
    try {
        $vip = $this->VipCommandService->create($command);
    } catch (Throwable $throwable) {
        $vip = $this->VipReadRepository->findVipType($this->appId, $this->type);
    }


    $this->assertEquals($this->appId, $vip->app_id);
    $this->assertEquals($this->type, $vip->type);

    return $vip;
});

test('can create a vip product', function () {

    $command = VipProductData::from([
        'appId'     => $this->appId,
        'type'      => $this->type,
        'timeUnit'  => TimeUnitEnum::MONTH,
        'timeValue' => 1,
        'price'     => [
            'currency' => 'CNY',
            'value'    => 100
        ],
        'name'      => '商品'
    ]);

    $result = $this->VipProductCommandService->create($command);


    $this->assertEquals($result->name, $command->name);

    return $result;

});

test('can buy a vip product', function (VipProduct $vipProduct) {


    $command = UserPurchaseVipCommand::from([
        'id'    => $vipProduct->id,
        'owner' => \Illuminate\Support\Facades\Auth::user(),
    ]);
    $this->VipPurchaseCommandService->buy($command);
})->depends('can create a vip product');
