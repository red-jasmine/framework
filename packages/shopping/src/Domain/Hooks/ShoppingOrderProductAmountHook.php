<?php

namespace RedJasmine\Shopping\Domain\Hooks;


use RedJasmine\Ecommerce\Domain\Data\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Support\Foundation\Hook\Closure;
use RedJasmine\Support\Foundation\Hook\Hookable;


/**
 * 购物订单获取商品基础价格组件
 *
 * @method static ProductAmountInfo hook(ProductPurchaseFactor $productPurchaseFactor, \Closure $closure)
 */
class ShoppingOrderProductAmountHook
{

    use Hookable;

    public static string $hook = 'shopping.domain.order.product.amount';


}
