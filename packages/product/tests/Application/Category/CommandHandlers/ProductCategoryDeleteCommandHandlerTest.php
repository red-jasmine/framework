<?php

namespace RedJasmine\Product\Tests\Application\Category\CommandHandlers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductCategoryDeleteCommandHandlerTest extends ApplicationTestCase
{


    protected function productCategoryRepository() : ProductCategoryRepositoryInterface
    {
        return app(ProductCategoryRepositoryInterface::class);
    }

    protected function productCategoryCommandService() : ProductCategoryCommandService
    {
        return app(ProductCategoryCommandService::class);
    }


    public function test_can_delete_product_category() : void
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
        $id =  $model->id;

        $deleteCommand = ProductCategoryDeleteCommand::from([
                                                                'id' => $id
                                                            ]);
        $this->productCategoryCommandService()->delete($deleteCommand);


        $this->expectException(ModelNotFoundException::class);

        $this->productCategoryRepository()->find($id);


    }

}
