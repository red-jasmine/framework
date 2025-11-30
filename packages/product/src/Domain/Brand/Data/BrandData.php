<?php

namespace RedJasmine\Product\Domain\Brand\Data;

use RedJasmine\Support\Domain\Data\BaseCategoryData;

/**
 * 品牌数据传输对象
 *
 * @property array|null $translations 翻译数据数组
 */
class BrandData extends BaseCategoryData
{
    /**
     * 翻译数据数组
     * 每个元素是一个 BrandTranslation 对象
     *
     * @var BrandTranslation[]|null
     */
    public ?array $translations = null;
}