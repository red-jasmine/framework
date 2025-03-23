<?php

namespace RedJasmine\Article\Domain\Data;

use RedJasmine\Article\Domain\Models\Enums\ArticleContentTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ArticleData extends Data
{


    public UserInterface $owner;

    public string $title;

    /**
     * @var ArticleContentTypeEnum
     */
    #[WithCast(EnumCast::class, ArticleContentTypeEnum::class)]
    public ArticleContentTypeEnum $contentType = ArticleContentTypeEnum::RICH;

    public string $content;

    public ?string $image = null;

    public ?string $description = null;

    public ?string $keywords = null;

    public ?int $categoryId = null;

    public int $sort = 0;

    public array $tags = [];
}