<?php

namespace RedJasmine\Order\Domain\Services;

use Cknow\Money\Money;
use RedJasmine\Order\Domain\Models\Order;


/**
 * 金额计算服务
 */
class OrderAmountCalculateService
{

    public function calculate(Order $order) : Order
    {
        // 统计商品金额
        $this->calculateProductsAmount($order);
        // 分摊运费 、 分摊优惠
        $this->calculateDivideDiscountAmount($order);
        //  计算税费
        $this->calculateTaxAmount($order);
        //  汇总订单金额
        $this->calculateOrderAmount($order);
        return $order;
    }

    /**
     * 计算商品金额
     *
     * @param  Order  $order
     *
     * @return void
     */
    protected function calculateProductsAmount(Order $order) : void
    {
        foreach ($order->products as $product) {
            // 总价 = 价格 * 数量
            $product->total_price = $product->price->multiply($product->quantity);
            // 单品优惠
            $product->discount_amount;
            // 商品总金额   < 0
            $product->product_amount = $product->total_price->subtract($product->discount_amount);

            // 小计类
            // 成本总价 = 成本价格 * 数量
            $product->total_cost_price = $product->cost_price?->multiply($product->quantity);

        }
    }

    protected function calculateOrderAmount(Order $order) : void
    {
        // 订单 邮费
        $order->freight_amount = $order->freight_amount ?? Money::parse(0, $order->currency);
        // 订单优惠
        $order->discount_amount;

        // 计算订单 总商品金额
        // 总商品金额 不包含 订单优惠、邮费、税费
        $order->total_product_amount = Money::sum(...$order->products->pluck('product_amount'));
        // 税费 = 商品
        $order->total_tax_amount = Money::sum(...$order->products->pluck('tax_amount'));

        // 订单应付金额 = 商品金额 + 邮费 - 优惠 +  税费
        $order->payable_amount = Money::sum($order->total_product_amount, $order->freight_amount)
                                      ->subtract($order->discount_amount)
                                      ->add($order->total_tax_amount);


        // 小计项目
        $order->total_product_discount_amount = Money::sum(...$order->products->pluck('discount_amount'));
        $order->total_price                   = Money::sum(...$order->products->pluck('price'));
        $order->total_cost_price              = Money::sum(...$order->products->pluck('total_cost_price'));


    }

    public static function divided(Money $amount, array $proportions = []) : array
    {
        if (empty($proportions)) {
            return [];  // 如果没有比例数组，直接返回全部金额
        }

        arsort($proportions);

        // 计算比例的总和
        $totalProportion = array_sum($proportions);


        $result = [];
        if ($totalProportion <= 0) {
            foreach ($proportions as $index => $proportion) {
                $result[$index] = $amount->subtract($amount);
            }
            return $result;
        }

        $indexCount = 0;
        foreach ($proportions as $index => $proportion) {
            if ($indexCount + 1 === count($proportions)) {
                // 最后一个
                $result[$index] = $amount->subtract(...$result);
            } else {
                $result[$index] = $amount->multiply(bcdiv($proportion, $totalProportion, 6), \Money\Money::ROUND_HALF_DOWN);

            }
        }
        return $result;

    }

    /**
     * 计算分摊优惠
     *
     * @param  Order  $order
     *
     * @return void
     */
    protected function calculateDivideDiscountAmount(Order $order) : void
    {

        // 获取商品分摊 比例
        $proportions = $order->products->pluck('product_amount', 'id')
                                       ->map(function ($productAmount) {
                                           return (int) $productAmount->getAmount();
                                       })->toArray();


        // 计算分摊订单优惠
        $discounts = static::divided($order->discount_amount, $proportions);
        // 计算分摊运费
        $freightFee = static::divided($order->freight_amount, $proportions);

        // 循环商品，设置分摊优惠金额
        foreach ($order->products as $product) {
            $product->divided_discount_amount    = $discounts[$product->id];
            $product->divided_freight_amount = $freightFee[$product->id];
        }
    }

    protected function calculateTaxAmount(Order $order) : void
    {
        // 计算商品 分摊后商品金额
        foreach ($order->products as $product) {
            // 分摊后的商品金额计算
            $product->divided_product_amount = Money::sum(
                $product->product_amount,
                $product->divided_discount_amount,
                $product->divided_freight_amount,
            );
            // 商品计税价格 =  商品金额 +  运费
            $productTaxPrice = $product->product_amount->add($product->divided_freight_amount);
            // 税费计算 = 商品计税价格 * 税率
            $product->tax_amount = $productTaxPrice->multiply(bcdiv($product->tax_rate, 100, 8));
            // 订单商品 应付金额
            $product->payable_amount = Money::sum($product->divided_product_amount, $product->tax_amount);
        }


    }

}