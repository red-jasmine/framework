<?php

namespace RedJasmine\Product\Domain\Attribute\Transformer;

use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 商品属性组转换器
 *
 * 负责将 ProductAttributeGroupData 转换为 ProductAttributeGroup 模型
 * 支持多语言翻译数据
 */
class ProductAttributeGroupTransformer extends CategoryTransformer implements TransformerInterface
{

}

