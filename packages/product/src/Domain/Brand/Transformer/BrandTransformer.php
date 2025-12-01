<?php

namespace RedJasmine\Product\Domain\Brand\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Brand\Data\BrandData;
use RedJasmine\Product\Domain\Brand\Data\BrandTranslation;
use RedJasmine\Product\Domain\Brand\Models\ProductBrand;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 品牌转换器
 *
 * 负责将 BrandData 转换为 ProductBrand 模型
 * 支持多语言翻译数据
 */
class BrandTransformer extends CategoryTransformer implements TransformerInterface
{

}

