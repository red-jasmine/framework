<?php


use Illuminate\Testing\TestResponse;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;


test('can create a article', function () {
    /**
     * @var $response TestResponse
     */
    $response = $this->post('api/article/articles', [
        'title'       => fake()->text(),
        'image'       => fake()->imageUrl(),
        'description' => fake()->text(),
        'keywords'    => fake()->words(5, true),
        'contentType' => ContentTypeEnum::RICH->value,
        'content'     => fake()->randomHtml(),
    ]);

    $response->assertSuccessful();

    return $response->json('data.id');

    // TODO 验证
});
test('can show a article', function ($id) {
    /**
     * @var $response TestResponse
     */
    $response = $this->get("api/article/articles/$id");

    $response->assertSuccessful();

    return $response->json('data.id');
})->depends('can create a article');


test('can update a article', function ($id) {
    /**
     * @var $response TestResponse
     */
    $response = $this->put("api/article/articles/$id", [
        'title'       =>fake()->text(),
        'image'       => fake()->imageUrl(),
        'description' => fake()->text(),
        'keywords'    => fake()->words(5, true),
        'contentType' => ContentTypeEnum::RICH->value,
        'content'     => fake()->randomHtml(),
    ]);

    $response->assertSuccessful();

    return $response->json('data.id');
})->depends('can create a article');
test('can delete a article', function ($id) {
    /**
     * @var $response TestResponse
     */
    $response = $this->delete("api/article/articles/$id");

    $response->assertSuccessful();

})->depends('can create a article');

test('can query article', function () {


    /**
     * @var $response TestResponse
     */
    $response = $this->get('api/article/articles?'.http_build_query([
            'include'     => 'content,tags,category',
            'category_id' => 559057812739439937,
        ]));

    $response->assertSuccessful();


});