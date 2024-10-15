<?php

namespace Brand\Queries;


use Illuminate\Support\Str;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Product\Tests\Application\Brand\BrandTestCase;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class BrandPaginateQueryTest extends BrandTestCase
{


    /**
     * 测试用例
     * 前提条件: 创建平拍
     * 步骤：
     *  1、能按ID查询品牌
     *  2、
     *  3、
     * 预期结果:
     *  1、 查询品牌信息
     *  2、
     * @return void
     */
    public function test_can_find_all() : void
    {

        $command = BrandCreateCommand::from([
                                                'parent_id'    => 0,
                                                'sort'         => fake()->numberBetween(0, 10000000),
                                                'name'         => fake()->name,
                                                'english_name' => fake('en')->name,
                                                'logo'         => fake()->imageUrl(200, 200),
                                                'initial'      => Str::upper(fake()->randomLetter()),
                                                'status'       => fake()->randomElement(BrandStatusEnum::values()),
                                                'expands'      => null,
                                                'is_show'      => true,
                                            ]);


        $brand = $this->brandCommandService()->create($command);
        $brandId = $brand->id;
        $query = PaginateQuery::from([
                                         'page'     => 1,
                                         'per_page' => 5,

                                     ]);

        $paginate = $this->brandQueryService()->paginate($query);

        $this->assertEquals($query->page, $paginate->currentPage());
        $this->assertEquals($query->perPage, $paginate->perPage());
        $paginate->total();

        $simplePaginate = $this->brandQueryService()->simplePaginate($query);

        $this->assertEquals($query->perPage, $simplePaginate->perPage());

    }

}
