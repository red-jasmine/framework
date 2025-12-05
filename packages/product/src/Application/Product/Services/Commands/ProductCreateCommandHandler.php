<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Application\Commands\CommandContext;

/**
 * @method  ProductApplicationService getService()
 */
class ProductCreateCommandHandler extends ProductCommandHandler
{
    protected string $name = 'create';

    // 需要组合 品牌服务、分类服务、卖家分类服务、属性服务
    protected function resolve(CommandContext $context) : Model
    {
        // 通过服务成处理
        $model = Product::make([]);
        $model->owner = $context->getCommand()->owner;

        return $model;
    }


}
