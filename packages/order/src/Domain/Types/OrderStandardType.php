<?php

namespace RedJasmine\Order\Domain\Types;

use Cknow\Money\Money;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\AcceptStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RateStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\SettlementStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderProduct;

class OrderStandardType implements OrderTypeInterface
{

    public static function label() : string
    {
        return '普通';
    }

    public static function type() : string
    {
        return 'standard';
    }

    protected array $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 计算订单金额
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function calculateAmount(Order $order) : void
    {
        // 统计商品金额
        $this->calculateProductsAmount($order);
        // 分摊运费 、 分摊优惠
        $this->calculateDivideDiscountAmount($order);
        //  计算税费
        $this->calculateTaxAmount($order);
        // 计算订单商品项应付金额

        $this->calculateOrderProductAmount($order);
        //  汇总订单金额
        $this->calculateOrderAmount($order);

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
        $order->product_amount = Money::sum(...$order->products->pluck('product_amount'));
        // 税费 = 商品
        $order->tax_amount = Money::sum(...$order->products->pluck('tax_amount'));
        // 服务费
        $order->service_amount = Money::sum(...$order->products->pluck('service_amount'));

        // 订单应付金额 = 商品金额 +服务费 + 邮费 + 税费 - 优惠
        $order->payable_amount = Money::sum(
            $order->product_amount,
            $order->freight_amount,
            $order->service_amount,
            $order->tax_amount,
        )->subtract(
            $order->discount_amount
        );

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
            $zero = Money::parse(0, $amount->getCurrency()->getCode());
            foreach ($proportions as $index => $proportion) {
                $result[$index] = $zero;
            }
            return $result;
        }

        $indexCount       = 0;
        $proportionsCount = count($proportions);
        foreach ($proportions as $index => $proportion) {
            $indexCount++;
            if ($indexCount === $proportionsCount) {
                // 最后一个
                if (empty($result)) {
                    $result[$index] = $amount;
                } else {
                    $result[$index] = $amount->subtract(...$result);
                }
            } else {

                $result[$index] = $amount->multiply(bcdiv($proportion, $totalProportion, 6));

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
            $product->divided_discount_amount = $discounts[$product->id];
            $product->freight_amount          = $freightFee[$product->id];
        }
    }

    protected function calculateTaxAmount(Order $order) : void
    {
        // 计算商品 分摊后商品金额
        foreach ($order->products as $product) {
            // 商品计税价格 =  商品金额 +  运费 TODO 分期情况是否添加运费 计算税费

            $productTaxPrice = $product->product_amount->add($product->freight_amount);
            // 税费计算 = 商品计税价格 * 税率
            $product->tax_amount = $productTaxPrice->multiply(bcdiv($product->tax_rate, 100, 8));
        }

    }


    protected function calculateOrderProductAmount(Order $order) : void
    {

        foreach ($order->products as $product) {
            $product->service_amount = $product->service_amount ?? Money::parse(0, $product->currency);
            // 订单商品 应付金额 = 商品金额 + 税费 + 服务费 + 分摊运费 - 分摊优惠
            $product->payable_amount = Money::sum(
                $product->product_amount,
                $product->tax_amount,
                $product->service_amount,
                $product->freight_amount,
            )->subtract(
                $product->divided_discount_amount
            );
        }

    }


    public function creating(Order $order) : void
    {
        // 初始化
        $order->order_status   = OrderStatusEnum::WAIT_BUYER_PAY;
        $order->payment_status = PaymentStatusEnum::WAIT_PAY;
        $order->products->each(function (OrderProduct $product) {
            $product->order_status   = OrderStatusEnum::WAIT_BUYER_PAY;
            $product->payment_status = PaymentStatusEnum::WAIT_PAY;
        });

        // 设置 支付超时时间

    }

    /**
     * @param  Order  $order
     *
     * @return void
     * @throws OrderException
     */
    public function paid(Order $order) : void
    {
        if ($order->payment_status !== PaymentStatusEnum::PAID) {
            return;
        }

        // 设置订单为 等待卖家确认中
        $order->order_status  = OrderStatusEnum::WAIT_SELLER_ACCEPT;
        $order->accept_status = AcceptStatusEnum::WAIT_ACCEPT;
        $order->products->each(function (OrderProduct $product) {
            $product->order_status = OrderStatusEnum::WAIT_SELLER_ACCEPT;
        });

        // 为自定接受
        if ($order->accept_timeout <= 0) {
            $order->accept();
        } else {
            // 设置最大自动接单时间
        }


    }

    /**
     * 接单操作
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function accept(Order $order) : void
    {
        $order->order_status    = OrderStatusEnum::WAIT_SELLER_SEND_GOODS;
        $order->shipping_status = ShippingStatusEnum::WAIT_SEND;
        $order->products->each(function (OrderProduct $product) {
            $product->order_status    = OrderStatusEnum::WAIT_SELLER_SEND_GOODS;
            $product->shipping_status = ShippingStatusEnum::WAIT_SEND;
        });
    }

    public function reject(Order $order) : void
    {

    }


    public function shipping(Order $order) : void
    {
        $order->products->each(function (OrderProduct $product) {
            if ($product->shipping_status === ShippingStatusEnum::SHIPPED && $product->isEffective()) {
                $product->order_status = OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS;
            }
        });

    }

    public function shipped(Order $order) : void
    {

        $order->products->each(function (OrderProduct $product) {
            if ($product->shipping_status === ShippingStatusEnum::SHIPPED && $product->isEffective()) {
                $product->order_status = OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS;
            }
        });
        if ($order->shipping_status === ShippingStatusEnum::SHIPPED) {
            $order->order_status = OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS;


            // 设置最大确认时间 TODO
        }

    }

    /**
     * 订单确认
     *
     * @param  Order  $order
     *
     * @return void
     */
    public function confirmed(Order $order) : void
    {
        $isAllConfirmed = true;
        foreach ($order->products as $product) {
            if ($product->shipping_status !== ShippingStatusEnum::SHIPPED && $product->isEffective()) {
                $isAllConfirmed = false;
            }
        }
        if ($isAllConfirmed) {
            $order->order_status      = OrderStatusEnum::FINISHED;
            $order->rate_status       = RateStatusEnum::WAIT_RATE;
            $order->settlement_status = SettlementStatusEnum::WAIT_SETTLEMENT;
            $order->products->each(function (OrderProduct $product) {
                if ($product->isEffective()) {
                    $product->order_status      = OrderStatusEnum::FINISHED;
                    $product->rate_status       = RateStatusEnum::WAIT_RATE;
                    $product->settlement_status = SettlementStatusEnum::WAIT_SETTLEMENT;
                }
            });
        }


    }

}