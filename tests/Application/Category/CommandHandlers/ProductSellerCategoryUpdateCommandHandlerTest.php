<?php

namespace Category\CommandHandlers;

use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductSellerCategoryUpdateCommandHandlerTest extends ApplicationTestCase
{


    protected function repository() : ProductSellerCategoryRepositoryInterface
    {
        return app(ProductSellerCategoryRepositoryInterface::class);
    }

    protected function commandService() : ProductSellerCategoryCommandService
    {
        return app(ProductSellerCategoryCommandService::class);
    }


    public function test_can_create_seller_product_category() : void
    {

        $command = ProductSellerCategoryCreateCommand::from([
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

        $command = ProductSellerCategoryUpdateCommand::from([
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


        $this->assertInstanceOf(ProductSellerCategory::class, $category);
        $this->assertEquals($command->name, $category->name);
        $this->assertEquals($command->parentId, $category->parent_id);


    }

}
