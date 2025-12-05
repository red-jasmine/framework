<?php

namespace RedJasmine\Product\Domain\Brand\Transformer;

use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Presets\Category\Domain\Transformer\CategoryTransformer;

/**
 * 品牌转换器
 *
 * 负责将 BrandData 转换为 ProductBrand 模型
 * 支持多语言翻译数据
 */
class BrandTransformer extends CategoryTransformer implements TransformerInterface
{

}

