<?php

namespace RedJasmine\Article\Domain\Data;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ArticleData extends Data
{


    public UserInterface $owner;

    public string $title;

    /**
     * @var ContentTypeEnum
     */
    #[WithCast(EnumCast::class, ContentTypeEnum::class)]
    public ContentTypeEnum $contentType = ContentTypeEnum::RICH;

    public string $content;

    public ?string $image = null;

    public ?string $description = null;

    public ?string $keywords = null;

    public ?int $categoryId = null;

    public int $sort = 0;

    public bool $isShow = false;

    public array $tags = [];
}