<?php

namespace RedJasmine\Article\Domain\Data;


use RedJasmine\Article\Domain\Models\Enums\TagStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ArticleTagData extends BaseCategoryData
{


    public UserInterface $owner;





}