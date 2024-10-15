<?php

namespace RedJasmine\Product\Tests\Application\Brand\CommandHandlers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandDeleteCommand;
use RedJasmine\Product\Domain\Brand\Models\Enums\BrandStatusEnum;
use RedJasmine\Product\Tests\Application\Brand\BrandTestCase;

class BrandDeleteCommandHandlerTest extends BrandTestCase
{


    /**
     * 能修改品牌
     * 前提条件: 创建品牌
     * 步骤：
     *  1、
     *  2、
     *  3、
     * 预期结果:
     *  1、
     *  2、
     * @return void
     */
    public function test_can_delete_brand() : void
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


        $brand   = $this->brandCommandService()->create($command);
        $brandId = $brand->id;
        $command = BrandDeleteCommand::from([ 'id' => $brand->id ]);
        $this->brandCommandService()->delete($command);

        $this->expectException(ModelNotFoundException::class);


        $this->brandRepository()->find($brandId);


    }


}
