<?php


namespace RedJasmine\Product\Tests\Application\Category\QueryHandlers;

use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Models\Enums\CategoryStatusEnum;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Product\Tests\Application\ApplicationTestCase;

class ProductCategoryTreeQueryHandlerTest extends ApplicationTestCase
{

    protected function productCategoryReadRepository() : ProductCategoryReadRepositoryInterface
    {
        return app(ProductCategoryReadRepositoryInterface::class);
    }

    protected function productCategoryQueryService() : ProductCategoryQueryService
    {
        return app(ProductCategoryQueryService::class);
    }

    protected function productCategoryCommandService() : ProductCategoryCommandService
    {
        return app(ProductCategoryCommandService::class);
    }

    /**
     * 测试能查询 出 树形
     * 前提条件: 创建条件
     * 步骤：
     *  1、
     *  2、
     *  3、
     * 预期结果:
     *  1、
     *  2、
     * @return void
     */
    public function test_can_tree_query() : void
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

        $command = ProductCategoryCreateCommand::from([
                                                          'name'       => fake()->name,
                                                          'parent_id'  => $id,
                                                          'status'     => CategoryStatusEnum::ENABLE->value,
                                                          'sort'       => fake()->numberBetween(0, 1000),
                                                          'is_leaf'    => false,
                                                          'is_show'    => false,
                                                          'group_name' => fake()->name,
                                                          'image'      => fake()->imageUrl,
                                                          'expands'    => [],
                                                      ]);


        $this->productCategoryCommandService()->create($command);


        $query = ProductCategoryTreeQuery::from([
                                                    'fields' => [ 'id', 'parent_id', 'name' ]
                                                ]);

        $tree = $this->productCategoryQueryService()->tree($query);

        $this->assertIsArray($tree);
    }

}
