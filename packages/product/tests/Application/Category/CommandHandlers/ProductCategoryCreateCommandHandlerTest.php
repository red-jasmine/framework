<?php

namespace RedJasmine\Product\Tests\Application\Category\CommandHandlers;

use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductCategoryCreateCommandHandlerTest extends ApplicationTestCase
{


    protected function productCategoryRepository() : ProductCategoryRepositoryInterface
    {
        return app(ProductCategoryRepositoryInterface::class);
    }

    protected function productCategoryCommandService() : ProductCategoryCommandService
    {
        return app(ProductCategoryCommandService::class);
    }


    public function test_can_create_product_category() : void
    {

        $command = ProductCategoryCreateCommand::from([
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


        $model = $this->productCategoryCommandService()->create($command);
        $id = $model->id;

        $productCategory = $this->productCategoryRepository()->find($id);


        $this->assertInstanceOf(ProductCategory::class, $productCategory);
        $this->assertEquals($command->name, $productCategory->name);
        $this->assertEquals($command->parentId, $productCategory->parent_id);


    }

}
