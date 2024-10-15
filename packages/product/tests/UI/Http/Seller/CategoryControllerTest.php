<?php

namespace RedJasmine\Product\Tests\UI\Http\Seller;

class CategoryControllerTest extends BaseTestCase
{


    public function test_can_query() : void
    {
        $response = $this->getJson(route('seller.product.categories.tree', [], false));

        $response->assertStatus(200);


        if ($id = $response->json('data.0.id')) {
            $showResponse = $this->getJson(route('seller.product.categories.show', [ 'category' => $id ], false));
            $showResponse->assertStatus(200);
            $this->assertEquals($id, $showResponse->json('data.id'));
        }
    }

}
