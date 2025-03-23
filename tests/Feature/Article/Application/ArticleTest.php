<?php


use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Application\Services\ArticleCategory\ArticleCategoryApplicationService;
use RedJasmine\Article\Application\Services\ArticleTag\ArticleTagApplicationService;
use RedJasmine\Article\Domain\Data\ArticleCategoryData;
use RedJasmine\Article\Domain\Data\ArticleData;
use RedJasmine\Article\Domain\Data\ArticleTagData;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Models\Enums\ArticleContentTypeEnum;
use RedJasmine\Article\Domain\Models\Enums\CategoryStatusEnum;
use RedJasmine\Article\Domain\Models\Enums\TagStatusEnum;

beforeEach(function () {

    $this->ArticleApplicationService = app(ArticleApplicationService::class);

    $this->ArticleTagApplicationService = app(ArticleTagApplicationService::class);

    $this->ArticleCategoryApplicationService = app(ArticleCategoryApplicationService::class);
});

test('can create a article category', function () {

    $command = new ArticleCategoryData();

    $command->parentId = 0;

    $command->name        = fake()->word();
    $command->description = fake()->text();
    $command->image       = fake()->imageUrl();
    $command->isLeaf      = false;
    $command->isShow      = true;
    $command->status      = CategoryStatusEnum::ENABLE;
    $command->sort        = 1;


    $result = $this->ArticleCategoryApplicationService->create($command);


    $command->parentId = $result->id;

    $command->name        = fake()->word();
    $command->description = fake()->text();
    $command->image       = fake()->imageUrl();
    $command->isLeaf      = true;
    $command->isShow      = true;
    $command->status      = CategoryStatusEnum::ENABLE;
    $command->sort        = 1;
    $result2              = $this->ArticleCategoryApplicationService->create($command);


    $this->assertEquals($command->name, $result2->name);
    $this->assertEquals($command->description, $result2->description);
    $this->assertEquals($command->image, $result2->image);
    $this->assertEquals($command->isLeaf, $result2->is_leaf);
    $this->assertEquals($command->isShow, $result2->is_show);
    $this->assertEquals($command->status, $result2->status);
    $this->assertEquals($command->sort, $result2->sort);
    $this->assertEquals($result->id, $result2->parent_id);

    return $result2;

});
test('can create a tag', function () {

    $command              = new ArticleTagData();
    $command->owner       = \Illuminate\Support\Facades\Auth::user();
    $command->name        = fake()->word();
    $command->description = fake()->text();
    $command->icon        = fake()->imageUrl();
    $command->color       = fake()->hexColor();
    $command->cluster     = fake()->word();
    $command->isShow      = true;
    $command->isPublic    = true;
    $command->status      = TagStatusEnum::ENABLE;
    $result               = $this->ArticleTagApplicationService->create($command);
    $this->assertEquals($command->name, $result->name);
    $this->assertEquals($command->description, $result->description);
    $this->assertEquals($command->icon, $result->icon);
    $this->assertEquals($command->color, $result->color);
    $this->assertEquals($command->cluster, $result->cluster);
    $this->assertEquals($command->isShow, $result->is_show);
    $this->assertEquals($command->isPublic, $result->is_public);
    $this->assertEquals($command->status, $result->status);
    $this->assertEquals(0, $result->sort);


    return $result;
});

test('can create a article', function (ArticleCategory $articleCategory, ArticleTag $articleTag) {


    $command              = new ArticleData();
    $command->owner       = \Illuminate\Support\Facades\Auth::user();
    $command->title       = fake()->text();
    $command->image       = fake()->imageUrl();
    $command->description = fake()->text();
    $command->keywords    = fake()->words(5, true);
    $command->contentType = ArticleContentTypeEnum::RICH;
    $command->content     = fake()->randomHtml();
    $command->categoryId  = $articleCategory->id;
    $command->tags        = [$articleTag->id];

    $result = $this->ArticleApplicationService->create($command);

    $this->assertEquals($command->categoryId, $result->category_id);
    $this->assertEquals($command->title, $result->title);
    $this->assertEquals($command->image, $result->image);
    $this->assertEquals($command->description, $result->description);
    $this->assertEquals($command->keywords, $result->keywords);
    $this->assertEquals($command->contentType, $result->content->content_type);
    $this->assertEquals($command->content, $result->content->content);


    return $result;
})->depends('can create a article category', 'can create a tag');

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

    $command->tags = $article->tags->pluck('id')->toArray();

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
