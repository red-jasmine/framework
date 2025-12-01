<?php

namespace RedJasmine\Product\Domain\Service\Transformer;

use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 商品服务转换器
 *
 * 负责将 ProductServiceData 转换为 ProductService 模型
 * 支持多语言翻译数据
 */
class ProductServiceTransformer extends CategoryTransformer implements TransformerInterface
{

}

