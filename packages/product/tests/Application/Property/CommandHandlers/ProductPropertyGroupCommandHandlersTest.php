<?php

namespace Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupCommandService;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Application\Property\CommandHandlers\ProductPropertyTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyGroupFaker;

class ProductPropertyGroupCommandHandlersTest extends ProductPropertyTestCase
{


    public function test_can_create() : void
    {

        $command = (new ProductPropertyGroupFaker())->createCommand();

        $productProperty = $this->groupCommandService()->create($command);

        $productProperty = $this->groupRepository()->find($productProperty->id);

        $this->assertEquals($command->name, $productProperty->name);

    }


    public function test_can_update() : void
    {

        $command = (new ProductPropertyGroupFaker)->createCommand();

        $productProperty = $this->groupCommandService()->create($command);


        $command = (new ProductPropertyGroupFaker)->updateCommand([
                                                                      'id' => $productProperty->id,

                                                                  ]);

        $this->groupCommandService()->update($command);

        $productProperty = $this->groupRepository()->find($productProperty->id);

        $this->assertEquals($command->name, $productProperty->name);
        $this->assertEquals($command->sort, $productProperty->sort);


    }

}
