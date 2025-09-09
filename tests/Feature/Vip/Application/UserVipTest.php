<?php

use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Vip\Application\Services\Commands\UserVipOpenCommand;
use RedJasmine\Vip\Application\Services\UserVipApplicationService;
use RedJasmine\Vip\Application\Services\VipApplicationService;
use RedJasmine\Vip\Application\Services\VipProductApplicationService;
use RedJasmine\Vip\Domain\Data\VipData;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;

beforeEach(function () {

    $this->VipCommandService        = app(VipApplicationService::class);
    $this->VipProductCommandService = app(VipProductApplicationService::class);
    $this->UserVipCommandService    = app(UserVipApplicationService::class);
    $this->VipRepository        = app(VipRepositoryInterface::class);
    $this->appId                    = 'test';
    $this->type                     = 'vip';

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
        $vip = $this->VipRepository->findVipType($this->appId, $this->type);
    }


    $this->assertEquals($this->appId, $vip->app_id);
    $this->assertEquals($this->type, $vip->type);

    return $vip;
});


test('open a user vip', function (Vip $vip) {

    $command = UserVipOpenCommand::from([
        'owner'       => \Illuminate\Support\Facades\Auth::user(),
        'appId'       => $this->appId,
        'type'        => $this->type,
        'timeValue'   => fake()->randomNumber(1),
        'timeUnit'    => fake()->randomElement(TimeUnitEnum::values()),
        'paymentType' => 'admin',
        'paymentId'   => fake()->numerify('paymentId-##########'),
    ]);


    $result = $this->UserVipCommandService->open($command);


    $this->assertTrue($result);

})->depends('create a vip');