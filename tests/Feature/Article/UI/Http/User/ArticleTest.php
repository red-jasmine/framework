<?php


test('can query article', function () {


    $response = $this->get('api/article/articles?'.http_build_query(['include' => 'content']));

    $response->assertSuccessful();
    // TODO 验证


});