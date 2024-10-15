<?php

namespace RedJasmine\Product\Tests\Application\Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyFaker;

class ProductPropertyCommandHandlersTest extends ProductPropertyTestCase
{


    public function test_can_create() : void
    {
        // 创建分组

        $group = $this->createGroup();

        $command         = (new ProductPropertyFaker)->createCommand([ 'group_id' => $group->id ]);
        $productProperty = $this->propertyCommandService()->create($command);

        $productProperty = $this->propertyRepository()->find($productProperty->id);

        $this->assertEquals($command->name, $productProperty->name);
        $this->assertEquals($command->groupId, $productProperty->group_id);

    }


    public function test_can_update() : void
    {
        $group   = $this->createGroup();
        $command = (new ProductPropertyFaker)->createCommand();

        $productProperty = $this->propertyCommandService()->create($command);
        $this->assertEquals(0, $productProperty->group_id);

        $command = (new ProductPropertyFaker)->updateCommand([ 'id'       => $productProperty->id,
                                                               'group_id' => $group->id
                                                             ]);

        $this->propertyCommandService()->update($command);

        $productProperty = $this->propertyRepository()->find($productProperty->id);

        $this->assertEquals($command->name, $productProperty->name);
        $this->assertEquals($command->sort, $productProperty->sort);
        $this->assertEquals($group->id, $productProperty->group_id);


    }

}
