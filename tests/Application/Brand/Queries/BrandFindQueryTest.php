<?php

namespace RedJasmine\Product\Tests\Application\Brand\Queries;


use Illuminate\Support\Str;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Product\Tests\Application\Brand\BrandTestCase;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class BrandFindQueryTest extends BrandTestCase
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
    public function test_can_find() : void
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

        $brand = $this->brandQueryService()->findById(FindQuery::make($brandId));


        $this->assertInstanceOf(Brand::class, $brand);
        $this->assertEquals($brandId, $brand->id);

    }

}
