<?php


use Illuminate\Testing\TestResponse;

test('can query article', function () {


    /**
     * @var $response TestResponse
     */
    $response = $this->get('api/article/articles?'.http_build_query([
            'include'     => 'content,tags,category',
            'category_id' => 559057812739439937,
        ]));

    $response->assertSuccessful();
    $response->ddJson();
    // TODO 验证


});