<?php


use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Application\Services\Article\Commands\ArticleUpdateCommand;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Models\Enums\ArticleContentTypeEnum;
use RedJasmine\Article\Domain\Data\ArticleData;

beforeEach(function () {

    $this->ArticleApplicationService = app(ArticleApplicationService::class);

});


test('can create a article', function () {


    $command              = new ArticleData();
    $command->owner       = \Illuminate\Support\Facades\Auth::user();
    $command->title       = fake()->text();
    $command->image       = fake()->imageUrl();
    $command->description = fake()->text();
    $command->keywords    = fake()->words(5, true);
    $command->contentType = ArticleContentTypeEnum::RICH;
    $command->content     = fake()->randomHtml();


    $result = $this->ArticleApplicationService->create($command);

    $this->assertEquals($command->title, $result->title);
    $this->assertEquals($command->image, $result->image);
    $this->assertEquals($command->description, $result->description);
    $this->assertEquals($command->keywords, $result->keywords);
    $this->assertEquals($command->contentType, $result->content->content_type);
    $this->assertEquals($command->content, $result->content->content);


    return $result;
});

test('can update a article', function (Article $article) {
    $command = new ArticleData();
    $command->setKey($article->id);
    $command->owner       = \Illuminate\Support\Facades\Auth::user();
    $command->title       = fake()->text();
    $command->image       = fake()->imageUrl();
    $command->description = fake()->text();
    $command->keywords    = fake()->words(5, true);
    $command->contentType = ArticleContentTypeEnum::RICH;
    $command->content     = fake()->randomHtml();


    $result = $this->ArticleApplicationService->update($command);

    $this->assertEquals($command->title, $result->title);
    $this->assertEquals($command->image, $result->image);
    $this->assertEquals($command->description, $result->description);
    $this->assertEquals($command->keywords, $result->keywords);
    $this->assertEquals($command->contentType, $result->content->content_type);
    $this->assertEquals($command->content, $result->content->content);


    return $result;

})->depends('can create a article');


// 提交审批

// 审批通过

// 发布
