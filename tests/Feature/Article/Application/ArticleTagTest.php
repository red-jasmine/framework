<?php


use RedJasmine\Article\Application\Services\ArticleTag\ArticleTagApplicationService;
use RedJasmine\Article\Domain\Data\ArticleTagData;
use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Models\Enums\TagStatusEnum;

beforeEach(function () {

    $this->ArticleTagApplicationService = app(ArticleTagApplicationService::class);
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

test('can update a tag', function (ArticleTag $articleTag) {
    $command = new ArticleTagData();
    $command->setKey($articleTag->id);
    $command->owner       = \Illuminate\Support\Facades\Auth::user();
    $command->name        = fake()->word();
    $command->description = fake()->text();
    $command->icon        = fake()->imageUrl();
    $command->color       = fake()->hexColor();
    $command->cluster     = fake()->word();
    $command->isShow      = true;
    $command->isPublic    = true;
    $command->status      = TagStatusEnum::DISABLE;
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
})->depends('can create a tag');