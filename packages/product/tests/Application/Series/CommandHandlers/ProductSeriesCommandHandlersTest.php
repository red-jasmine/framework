<?php

namespace RedJasmine\Product\Tests\Application\Series\CommandHandlers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Application\Series\Services\ProductSeriesCommandService;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductSeriesCommandHandlersTest extends ApplicationTestCase
{

    public function commandService() : ProductSeriesCommandService
    {
        return app(ProductSeriesCommandService::class);
    }

    public function repository() : ProductSeriesRepositoryInterface
    {
        return app(ProductSeriesRepositoryInterface::class);
    }


    public function test_can_create_series() : void
    {
        $command = ProductSeriesCreateCommand::from([
                                                        'owner'    => $this->user(),
                                                        'name'     => fake()->name,
                                                        'remarks'  => fake()->text(),
                                                        'products' => [
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => 1,
                                                                'name'       => fake()->name,
                                                            ],
                                                        ],
                                                    ]);


        $model = $this->commandService()->create($command);


        $model = $this->repository()->find($model->id);


        $this->assertEquals($command->name, $model->name);
        $this->assertEquals($command->remarks, $model->remarks);
        $this->assertEquals($command->owner->getType(), $model->owner->getType());
        $this->assertEquals($command->owner->getID(), $model->owner->getID());

        $this->assertEquals($command->products->count(), $model->products->count());


    }


    public function test_can_update_series() : void
    {
        $command = ProductSeriesCreateCommand::from([
                                                        'owner'    => $this->user(),
                                                        'name'     => fake()->name,
                                                        'remarks'  => fake()->text(),
                                                        'products' => [
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => 1,
                                                                'name'       => fake()->name,
                                                            ],
                                                        ],
                                                    ]);


        $model = $this->commandService()->create($command);


        $command = ProductSeriesUpdateCommand::from([
                                                        'id'       => $model->id,
                                                        'owner'    => $this->user(),
                                                        'name'     => fake()->name,
                                                        'remarks'  => fake()->text(),
                                                        'products' => [
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => 1,
                                                                'name'       => fake()->name,
                                                            ],
                                                        ],
                                                    ]);


        $this->commandService()->update($command);


        $model = $this->repository()->find($model->id);


        $this->assertEquals($command->name, $model->name);
        $this->assertEquals($command->remarks, $model->remarks);
        $this->assertEquals($command->owner->getType(), $model->owner->getType());
        $this->assertEquals($command->owner->getID(), $model->owner->getID());
        $this->assertEquals($command->products->count(), $model->products->count());


    }


    public function test_can_delete_series() : void
    {
        $command = ProductSeriesCreateCommand::from([
                                                        'owner'    => $this->user(),
                                                        'name'     => fake()->name,
                                                        'remarks'  => fake()->text(),
                                                        'products' => [
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => fake()->numberBetween(1, 1000),
                                                                'name'       => fake()->name,
                                                            ],
                                                            [
                                                                'product_id' => 1,
                                                                'name'       => fake()->name,
                                                            ],
                                                        ],
                                                    ]);


        $model = $this->commandService()->create($command);


        $delete = ProductSeriesDeleteCommand::from([ 'id' => $model->id ]);
        $this->commandService()->delete($delete);
        $this->expectException(ModelNotFoundException::class);
        $model = $this->repository()->find($model->id);


    }
}
