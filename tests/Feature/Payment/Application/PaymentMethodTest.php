<?php


use RedJasmine\Payment\Application\Services\Method\MethodApplicationService;
use RedJasmine\Payment\Domain\Data\MethodData;
use RedJasmine\Payment\Domain\Repositories\MethodRepositoryInterface;

beforeEach(function () {

    $this->repository     = app(MethodRepositoryInterface::class);
    $this->commandService = app(MethodApplicationService::class);


    //
});

test('can create a method', function () {
    $command = new MethodData();

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
