<?php

namespace RedJasmine\Product\Domain\Group\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Group\Data\GroupData;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 商品分组转换器
 *
 * 负责将 GroupData 转换为 ProductGroup 模型
 * 支持多语言翻译数据和所属者设置
 */
class GroupTransformer extends CategoryTransformer implements TransformerInterface
{

}

