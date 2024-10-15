<?php

namespace RedJasmine\Product\Tests\UI\Http\Seller;

class BrandControllerTest extends BaseTestCase
{


    public function test_can_index() : void
    {
        $response = $this->getJson(route('seller.product.brands.index', [], false));

        $response->assertStatus(200);


        if ($id = $response->json('data.0.id')) {
            $showResponse = $this->getJson(route('seller.product.brands.show', [ 'brand' => $id ], false));
            $showResponse->assertStatus(200);
            $this->assertEquals($id, $showResponse->json('data.id'));
        }

    }

}
