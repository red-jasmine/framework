<?php

namespace RedJasmine\Product\Tests\UI\Http\Admin;


use RedJasmine\Product\Tests\Fixtures\Brand\BrandFaker;

class BrandControllerTest extends BaseTestCase
{


    public function test_can_index() : void
    {
        $response = $this->getJson(route('admin.product.brands.index', [], false));

        $response->assertStatus(200);


        if ($id = $response->json('data.0.id')) {
            $showResponse = $this->getJson(route('admin.product.brands.show', [ 'brand' => $id ], false));
            $showResponse->assertOk();
            $this->assertEquals($id, $showResponse->json('data.id'));
        }

    }

    public function test_can_create() : void
    {

        $data     = (new BrandFaker())->data();
        $response = $this->postJson(route('admin.product.brands.store', [], false), $data);
        $response->assertCreated();

    }

    public function test_can_update() : void
    {
        $data     = (new BrandFaker())->data();
        $response = $this->postJson(route('admin.product.brands.store', [], false), $data);
        $response->assertCreated();
        $data     = (new BrandFaker())->data();
        $response = $this->putJson(route('admin.product.brands.update', [ 'brand' => $response->json('data.id') ], false), $data);

        $response->assertOk();

    }


    public function test_can_destroy() : void
    {
        $data     = (new BrandFaker())->data();
        $response = $this->postJson(route('admin.product.brands.store', [], false), $data);
        $response = $this->deleteJson(route('admin.product.brands.destroy', [ 'brand' => $response->json('data.id') ], false));
        $response->assertOk();
    }

}
