<?php

namespace RedJasmine\Community\Domain\Data;


use RedJasmine\Community\Domain\Models\Enums\TagStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class TopicTagData extends BaseCategoryData
{

}