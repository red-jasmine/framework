<?php

namespace RedJasmine\Product\Tests\Application\Product\CommandHandlers;

use RedJasmine\Product\Tests\Fixtures\Product\ProductFaker;

class ProductUpdateCommandTest extends ProductTestCase
{


    public function test_can_update_product() : void
    {


        $command = (new ProductFaker())->createCommand($this->buildSkusData([
                                                                                '颜色' => [ '白色' => null, '黑色' => null, ],
                                                                                '尺码' => [ 'L' => null,'M' => null, 'XL' => null],
                                                                            ]));


        $command->isMultipleSpec = true;

        $product = $this->commandService()->create($command);



        $skus          = $this->buildSkusData(
            [
                '颜色' => [ '白色' => '白金', '黑色' => null,'绿色' => null, ],
                '尺码' => [ 'S' => null, 'M' => null, 'L' => null, 'XL' => null ]
            ]
        );
        $updateCommand = (new ProductFaker())->updateCommand(
            array_merge($skus, [ 'id' => $product->id ]));


        $updateCommand->isMultipleSpec = true;

        $this->commandService()->update($updateCommand);


        // TODO
        $this->assertTrue(true);


    }

}
