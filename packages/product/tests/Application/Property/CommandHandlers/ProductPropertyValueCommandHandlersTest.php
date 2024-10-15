<?php

namespace Property\CommandHandlers;

use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;
use RedJasmine\Product\Tests\Application\Property\CommandHandlers\ProductPropertyTestCase;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyFaker;
use RedJasmine\Product\Tests\Fixtures\Property\ProductPropertyValueFaker;

class ProductPropertyValueCommandHandlersTest extends ProductPropertyTestCase
{


    public function test_can_create() : void
    {
        $group = $this->createGroup();

        // 创建属性
        $command         = (new ProductPropertyFaker)->createCommand();
        $productProperty = $this->propertyCommandService()->create($command);


        $valueCreateCommand = (new ProductPropertyValueFaker())->createCommand([
                                                                                   'pid'      => $productProperty->id,
                                                                                   'group_id' => $group->id
                                                                               ]);

        $productPropertyValue = $this->valueCommandService()->create($valueCreateCommand);

        $productPropertyValue = $this->valueRepository()->find($productPropertyValue->id);


        $this->assertEquals($valueCreateCommand->pid, $productPropertyValue->pid);
        $this->assertEquals($valueCreateCommand->sort, $productPropertyValue->sort);
        $this->assertEquals($group->id, $productPropertyValue->group_id);
    }


    public function test_can_update() : void
    {
        $group = $this->createGroup();
        // 创建属性
        $command         = (new ProductPropertyFaker)->createCommand();
        $productProperty = $this->propertyCommandService()->create($command);


        $valueCreateCommand = (new ProductPropertyValueFaker())->createCommand([ 'pid' => $productProperty->id ]);

        $productPropertyValue = $this->valueCommandService()->create($valueCreateCommand);


        $valueUpdateCommand = (new ProductPropertyValueFaker())->updateCommand([
                                                                                   'id'       => $productPropertyValue->id,
                                                                                   'group_id' => $group->id
                                                                               ]);

        $this->valueCommandService()->update($valueUpdateCommand);

        $productPropertyValue = $this->valueRepository()->find($valueUpdateCommand->id);

        $this->assertEquals($valueUpdateCommand->id, $productPropertyValue->id);
        $this->assertEquals($valueUpdateCommand->name, $productPropertyValue->name);
        $this->assertEquals($valueUpdateCommand->sort, $productPropertyValue->sort);
        $this->assertEquals($group->id, $productPropertyValue->group_id);

    }

}
