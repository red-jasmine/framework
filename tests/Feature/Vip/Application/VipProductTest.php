<?php

use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Vip\Application\Services\UserVipApplicationService;
use RedJasmine\Vip\Application\Services\VipApplicationService;
use RedJasmine\Vip\Application\Services\VipProductApplicationService;
use RedJasmine\Vip\Domain\Data\VipData;
use RedJasmine\Vip\Domain\Data\VipProductData;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipProductRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;

beforeEach(function () {

    $this->VipCommandService = app(VipApplicationService::class);

    $this->UserVipCommandService = app(UserVipApplicationService::class);
    $this->VipReadRepository     = app(VipReadRepositoryInterface::class);
    $this->appId                 = 'test';
    $this->type                  = 'vip';


    $this->VipProductCommandService = app(VipProductApplicationService::class);
    $this->VipProductReadRepository = app(VipProductReadRepositoryInterface::class);
    $this->VipProductRepository     = app(VipProductRepositoryInterface::class);

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
            'value'    => 10
        ],
        'name'      => '商品'
    ]);

    $result = $this->VipProductCommandService->create($command);


    $this->assertEquals($result->name, $command->name);

    return $result;

});

test('can find a vip product', function (VipProduct $product) {


    $result = $this->VipProductRepository->find($product->id);


    $this->assertEquals($product->id, $result->id);
    $this->assertEquals($product->app_id, $result->app_id);


})->depends('can create a vip product');


test('can update a vip product', function (VipProduct $product) {
    $command = VipProductData::from([
        'appId'     => $this->appId,
        'type'      => $this->type,
        'timeUnit'  => TimeUnitEnum::YEAR,
        'timeValue' => 3,
        'price'     => [
            'currency' => 'CNY',
            'value'    => '300'
        ],
        'name'      => '3年'
    ]);
    $command->setKey($product->id);

    $result = $this->VipProductCommandService->update($command);


    $this->assertEquals($result->name, $command->name);

    return $result;


})->depends('can create a vip product');


