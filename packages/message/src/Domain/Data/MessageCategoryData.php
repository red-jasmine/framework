<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Data;

use RedJasmine\Message\Domain\Models\Enums\BizEnum;
use RedJasmine\Message\Domain\Models\Enums\StatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息分类数据传输对象
 */
class MessageCategoryData extends BaseCategoryData
{
    public string $biz;

    public UserInterface $user;
}
