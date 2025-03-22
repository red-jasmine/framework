<?php

use RedJasmine\Article\Application\Services\ArticleCategory\ArticleCategoryApplicationService;
use RedJasmine\Article\Domain\Data\ArticleCategoryData;
use RedJasmine\Article\Domain\Models\Enums\CategoryStatusEnum;

beforeEach(function () {

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
    $result2 = $this->ArticleCategoryApplicationService->create($command);


    $this->assertEquals($command->name, $result2->name);
    $this->assertEquals($command->description, $result2->description);
    $this->assertEquals($command->image, $result2->image);
    $this->assertEquals($command->isLeaf, $result2->is_leaf);
    $this->assertEquals($command->isShow, $result2->is_show);
    $this->assertEquals($command->status, $result2->status);
    $this->assertEquals($command->sort, $result2->sort);
    $this->assertEquals($result->id, $result2->parent_id);

});