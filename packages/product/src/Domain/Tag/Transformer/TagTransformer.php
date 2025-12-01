<?php

namespace RedJasmine\Product\Domain\Tag\Transformer;

use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 商品标签转换器
 *
 * 负责将 TagData 转换为 ProductTag 模型
 * 支持多语言翻译数据
 */
class TagTransformer extends CategoryTransformer implements TransformerInterface
{

}

