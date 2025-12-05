<?php

namespace RedJasmine\Product\Domain\Group\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;

/**
 * 商品分组数据传输对象
 *
 * @property UserInterface $owner 所属者
 * @property array|null $translations 翻译数据数组
 */
class GroupData extends BaseCategoryData
{
    public UserInterface $owner;
}

