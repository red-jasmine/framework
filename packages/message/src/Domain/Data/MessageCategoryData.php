<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Data;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;

/**
 * 消息分类数据传输对象
 */
class MessageCategoryData extends BaseCategoryData
{
    public string $biz;

    public UserInterface $user;
}
