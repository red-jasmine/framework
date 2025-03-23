<?php

namespace RedJasmine\Article\Domain\Data;


use RedJasmine\Article\Domain\Models\Enums\TagStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ArticleTagData extends Data
{


    public UserInterface $owner;

    public string $name;

    public ?string $description;

    public bool $isShow = false;

    public bool $isPublic = false;

    #[WithCast(EnumCast::class, TagStatusEnum::class)]
    public TagStatusEnum $status = TagStatusEnum::ENABLE;

    public int $sort = 0;

    public ?string $cluster = null;

    public ?string $icon = null;

    public ?string $color = null;



}