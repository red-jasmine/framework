<?php

namespace RedJasmine\User\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;
use RedJasmine\User\Domain\Enums\UserTagStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserTagData extends BaseCategoryData
{

}