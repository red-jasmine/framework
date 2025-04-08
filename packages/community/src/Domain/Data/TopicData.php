<?php

namespace RedJasmine\Community\Domain\Data;


use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class TopicData extends Data
{


    public UserInterface $owner;

    public string $title;

    #[WithCast(EnumCast::class, ContentTypeEnum::class)]
    public ContentTypeEnum $contentType = ContentTypeEnum::TEXT;

    public string $content;

    public ?string $image = null;

    public ?string $description = null;

    public ?string $keywords = null;

    public ?int $categoryId = null;

    public int $sort = 0;

    public array $tags = [];
}