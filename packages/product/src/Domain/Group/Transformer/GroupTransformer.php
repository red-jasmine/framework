<?php

namespace RedJasmine\Product\Domain\Group\Transformer;

use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 商品分组转换器
 *
 * 负责将 GroupData 转换为 ProductGroup 模型
 * 支持多语言翻译数据
 */
class GroupTransformer extends CategoryTransformer implements TransformerInterface
{

}

