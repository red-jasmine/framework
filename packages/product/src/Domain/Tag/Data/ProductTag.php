<?php

namespace RedJasmine\Product\Domain\Tag\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\BaseCategoryData;

/**
 * 商品标签数据传输对象
 *
 * @property UserInterface $owner 所属者
 * @property array|null $translations 翻译数据数组
 */
class ProductTag extends BaseCategoryData
{
    public UserInterface $owner;
}
