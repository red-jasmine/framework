<?php


use RedJasmine\Payment\Application\Services\PlatformCommandService;
use RedJasmine\Payment\Domain\Data\PlatformData;
use RedJasmine\Payment\Domain\Repositories\PlatformRepositoryInterface;

beforeEach(function () {

    $this->repository     = app(PlatformRepositoryInterface::class);
    $this->commandService = app(PlatformCommandService::class);


    //
});

test('can create a platform', function () {
    $command = new PlatformData();

    $command->code    = fake()->word();
    $command->name    = fake()->name();
    $command->icon    = fake()->imageUrl(40, 40);
    $command->remarks = fake()->text();

    $model = $this->commandService->create($command);
    $this->assertEquals($command->code, $model->code);
    $this->assertEquals($command->name, $model->name);
    $this->assertEquals($command->icon, $model->icon);
    $this->assertEquals($command->remarks, $model->remarks);

});
