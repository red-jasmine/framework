<?php

namespace Product\CommandHandlers;

use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Tests\Application\Product\CommandHandlers\ProductTestCase;
use RedJasmine\Product\Tests\Fixtures\Product\ProductFaker;

class ProductCreateCommandTest extends ProductTestCase
{


    public function brandCommandService() : BrandCommandService
    {
        return app(BrandCommandService::class);
    }


    public function test_can_create_product() : void
    {


        $data = [];


        $brand = $this->brandCommandService()->create(BrandCreateCommand::from([ 'name' => '李宁' ]));

        $data['brand_id']    = $brand->id;
        $data['category_id'] = 0;
        $command             = (new ProductFaker())->createCommand($data);

        $this->commandService()->create($command);
        // TODO
        $this->assertTrue(true);
    }


    /**
     * 能创建多规格商品
     * 前提条件: 创建好、属性、属性值
     * 步骤：
     *  1、组装 属性值
     *  2、创建商品
     *  3、
     * 预期结果:
     *  1、规格数量一致
     *  2、库存汇总
     * @return void
     */
    public function test_can_create_multiple_spec_product() : void
    {


        // 构件基础属性
        $data                = [];
        $data['basic_props'] = $this->buildBasicProperties();

        $command = (new ProductFaker())->createCommand(array_merge($data, $this->buildSkusData()));


        $command->isMultipleSpec = true;
        $this->commandService()->create($command);

        // TODO
        $this->assertTrue(true);
    }

}
