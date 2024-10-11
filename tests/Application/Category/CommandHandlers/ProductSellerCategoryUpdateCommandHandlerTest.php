<?php

namespace Category\CommandHandlers;

use RedJasmine\Product\Application\Group\Services\ProductGroupCommandService;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductSellerCategoryUpdateCommandHandlerTest extends ApplicationTestCase
{


    protected function repository() : ProductGroupRepositoryInterface
    {
        return app(ProductGroupRepositoryInterface::class);
    }

    protected function commandService() : ProductGroupCommandService
    {
        return app(ProductGroupCommandService::class);
    }


    public function test_can_create_seller_product_category() : void
    {

        $command = ProductGroupCreateCommand::from([
                                                                'owner'      => $this->user(),
                                                                'name'       => fake()->name,
                                                                'parent_id'  => 0,
                                                                'status'     => CategoryStatusEnum::ENABLE->value,
                                                                'sort'       => fake()->numberBetween(0, 1000),
                                                                'is_leaf'    => false,
                                                                'is_show'    => false,
                                                                'group_name' => fake()->name,
                                                                'image'      => fake()->imageUrl,
                                                                'expands'    => [],
                                                            ]);


        $model = $this->commandService()->create($command);
        $id =  $model->id;

        $command = ProductGroupUpdateCommand::from([
                                                                'id'         => $id,
                                                                'owner'      => $this->user(),
                                                                'name'       => fake()->name,
                                                                'parent_id'  => 0,
                                                                'status'     => CategoryStatusEnum::ENABLE->value,
                                                                'sort'       => fake()->numberBetween(0, 1000),
                                                                'is_leaf'    => false,
                                                                'is_show'    => false,
                                                                'group_name' => fake()->name,
                                                                'image'      => fake()->imageUrl,
                                                                'expands'    => [],
                                                            ]);


        $this->commandService()->update($command);


        $category = $this->repository()->find($id);


        $this->assertInstanceOf(ProductGroup::class, $category);
        $this->assertEquals($command->name, $category->name);
        $this->assertEquals($command->parentId, $category->parent_id);


    }

}
