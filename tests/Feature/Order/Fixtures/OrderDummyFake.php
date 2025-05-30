<?php

namespace RedJasmine\Tests\Feature\Order\Fixtures;

use Illuminate\Support\Facades\Auth;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCardKeyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderDummyShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderLogisticsShippingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommand;
use RedJasmine\Order\Domain\Data\OrderPaymentData;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;


class OrderDummyFake
{


    public OrderTypeEnum $orderType = OrderTypeEnum::STANDARD;

    /**
     * 发货类型
     * @var ShippingTypeEnum
     */
    public ShippingTypeEnum $shippingType = ShippingTypeEnum::LOGISTICS;
    // 商品数量
    public int    $productCount = 3;
    public string $currency     = 'CNY';


    public string $unit         = '件';
    public int    $unitQuantity = 1;

    public int $payment_timeout = -1;
    public int $accept_timeout  = 0;
    public int $confirm_timeout = -1;
    public int $rate_timeout    = -1;

    public function order(array $order = []) : array
    {
        $orderDataArray = $this->fakeOrderArray($order);
        for ($i = 1; $i <= $this->productCount; $i++) {
            $orderDataArray['products'][] = $this->fakeProductArray();
        }
        return $orderDataArray;
    }

    public function fakeOrderArray(array $order = []) : array
    {
        $user = Auth::user();


        $fake = [
            'buyer'           => [
                'type'     => $user->getType(),
                'id'       => $user->getId(),
                'nickname' => fake()->name(),
            ],
            'seller'          => [
                'type'     => 'seller',
                'id'       => fake()->numberBetween(1000000, 999999999),
                'nickname' => fake()->name(),
            ],
            'currency'        => $this->currency,
            'title'           => fake()->text(),
            'order_type'      => $this->orderType->value,
            'shipping_type'   => $this->shippingType->value,
            'source_type'     => fake()->randomElement(['product', 'activity']),
            'source_id'       => fake()->numerify('out-order-id-########'),
            'outer_order_id'  => fake()->numerify('out-order-id-########'),
            'payment_timeout' => $this->payment_timeout,
            'accept_timeout'  => $this->accept_timeout,
            'confirm_timeout' => $this->confirm_timeout,
            'rate_timeout'    => $this->rate_timeout,
            'channel'         => [
                'type'     => fake()->randomElement(['channel',]),
                'id'       => fake()->randomNumber(5, true),
                'nickname' => fake()->name(),
            ],

            'store' => [
                'type'     => fake()->randomElement(['self', 'franchise']),
                'id'       => fake()->randomNumber(5, true),
                'nickname' => fake()->name(),
            ],
            'guide' => [
                'type'     => fake()->randomElement(['guide']),
                'id'       => fake()->randomNumber(5, true),
                'nickname' => fake()->name(),
            ],

            'freight_amount'  => [
                'currency' => $this->currency,
                'amount'   => fake()->randomFloat(0, 5, 10),
            ],
            'discount_amount' => [
                'currency' => $this->currency,
                'amount'   => fake()->randomFloat(0, 5, 10),
            ],
            'contact'         => fake()->phoneNumber(),
            'password'        => fake()->password(6),
            'client_type'     => fake()->randomElement(['h5', 'ios-app', 'applets']),
            'client_version'  => fake()->randomNumber(),
            'client_ip'       => fake()->ipv4(),
            'seller_remarks'  => fake()->sentence(10),
            'seller_message'  => fake()->sentence(10),
            'buyer_remarks'   => fake()->sentence(10),
            'buyer_message'   => fake()->sentence(10),
            'seller_extends'  => [],
            'buyer_extends'   => [],
            'other_extends'   => [],
            'tools'           => [],
            'address'         => $this->fakeAddressArray(),
        ];
        return array_merge($fake, $order);
    }

    public function fakeAddressArray() : array
    {
        return [
            'contacts'   => fake()->name,
            'mobile'     => fake()->phoneNumber(),
            'country'    => fake()->country(),
            'province'   => fake()->city(),
            'city'       => fake()->city(),
            'district'   => fake()->city,
            'street'     => fake()->streetName(),
            'address'    => fake()->address(),
            'zip_code'   => fake()->numerify('######'),
            'lon'        => fake()->longitude(),
            'lat'        => fake()->latitude(),
            'countryId'  => 0,
            'provinceId' => 110000,
            'cityId'     => 111100,
            'districtId' => 111111,
            'streetId'   => null,
            'extends'    => [],

        ];

    }

    public function fakeProductArray(array $product = []) : array
    {
        $fake = [
            'shipping_type'      => $this->shippingType->value,
            'order_product_type' => ProductTypeEnum::GOODS->value,
            'title'              => fake()->sentence(),
            'sku_name'           => fake()->words(1, true),
            'image'              => fake()->imageUrl,
            'product_type'       => 'product',
            'product_id'         => fake()->numberBetween(1000000, 999999999),
            'sku_id'             => fake()->numberBetween(1000000, 999999999),
            'category_id'        => 0,
            'brand_id'           => 2,
            'product_group_id'   => 0,
            'outer_product_id'   => fake()->numerify('outer_product_id-########'),
            'outer_sku_id'       => fake()->numerify('outer_sku_id-########'),
            'barcode'            => fake()->ean13(),
            'quantity'           => fake()->numberBetween(1, 10),
            'unit'               => $this->unit,
            'unit_quantity'      => $this->unitQuantity,
            'price'              => [
                'currency' => $this->currency,
                'amount'   => fake()->randomFloat(2, 90, 100),
            ],
            'cost_price'         => [
                'currency' => $this->currency,
                'amount'   => fake()->randomFloat(2, 70, 80),
            ],
            'tex_rate'           => 7,// %

            'discount_amount'        => [
                'currency' => $this->currency,
                'amount'   => fake()->randomFloat(2, 5, 20),
            ],
            'outer_order_product_id' => fake()->numerify('CODE-########'),
            'seller_remarks'         => fake()->sentence(10),
            'seller_message'         => fake()->sentence(10),
            'buyer_remarks'          => fake()->sentence(10),
            'buyer_message'          => fake()->sentence(10),
            'seller_extends'         => [],
            'buyer_extends'          => [],
            'other_extends'          => [],
            'tools'                  => [],
            'form'                   => [],
            'after_sales_services'   => [
                AfterSalesService::from([
                    'refundType'    => RefundTypeEnum::REFUND->value,
                    'allowStage'    => OrderAfterSaleServiceAllowStageEnum::SIGNED,
                    'timeLimit'     => 7,
                    'timeLimitUnit' => OrderAfterSaleServiceTimeUnit::Day,
                ])->toArray(),
                AfterSalesService::from([
                    'refundType'    => RefundTypeEnum::EXCHANGE->value,
                    'allowStage'    => OrderAfterSaleServiceAllowStageEnum::SIGNED,
                    'timeLimit'     => 7,
                    'timeLimitUnit' => OrderAfterSaleServiceTimeUnit::Day,
                ])->toArray(),
                AfterSalesService::from([
                    'refundType'    => RefundTypeEnum::WARRANTY->value,
                    'allowStage'    => OrderAfterSaleServiceAllowStageEnum::SIGNED,
                    'timeLimit'     => 180,
                    'timeLimitUnit' => OrderAfterSaleServiceTimeUnit::Day,
                ])->toArray(),

            ],
        ];
        return array_merge($fake, $product);
    }

    public function paid(array $merge = []) : OrderPaidCommand
    {

        $data = [
            'id'                  => 1,
            'order_payment_id'    => 1,
            'amount'              => 0,
            'payment_time'        => date('Y-m-d H:i:s'),
            'payment_type'        => 'payment',
            'payment_id'          => fake()->numberBetween(1000000, 999999999),
            'payment_channel'     => fake()->randomNumber(['alipay', 'wechat']),
            'payment_channel_no'  => fake()->numerify('out-sku-id-########'),
            'payment_method_type' => fake()->randomElement(['h5', 'applets', 'ios-app', 'android']),
        ];

        $data = array_merge($data, $merge);
        return OrderPaidCommand::from($data);
    }


    public function shippingLogistics(array $merge = []) : OrderLogisticsShippingCommand
    {
        $data = [
            'order_no'               => 1,
            'is_split'               => false,
            'is_finished'            => true,
            'order_products'         => null,
            'logistics_company_code' => fake()->randomElement(['shunfeng', 'yuantong',]),
            'logistics_no'           => fake()->numerify('##########'),
        ];

        $data = array_merge($data, $merge);

        return OrderLogisticsShippingCommand::from($data);
    }


    public function shippingCardKey(array $merge) : OrderCardKeyShippingCommand
    {
        $data = [
            'id'               => 1,
            'order_product_id' => 0,
            'content'          => fake()->text(),
            'extends'          => [],
        ];

        $data = array_merge($data, $merge);
        return OrderCardKeyShippingCommand::from($data);
    }

    public function shippingDummy(array $merge) : OrderDummyShippingCommand
    {
        $data = [
            'order_no'         => null,
            'order_product_id' => 0,
            'is_finished'      => true,
        ];

        $data = array_merge($data, $merge);
        return OrderDummyShippingCommand::from($data);
    }


    public function progress(array $merge = []) : OrderProgressCommand
    {
        $data = [
            'id'               => 0,
            'order_product_id' => 0,
            'progress'         => 1,
            'is_absolute'      => true,
            'is_allow_less'    => false,
        ];

        $data = array_merge($data, $merge);
        return OrderProgressCommand::from($data);
    }


    public function createRefund(array $merge = []) : RefundCreateCommand
    {
        $data = [
            'id'               => 0,
            'order_product_id' => 0,
            'images'           => [fake()->imageUrl, fake()->imageUrl],
            'refund_type'      => RefundTypeEnum::REFUND->value,
            'reason'           => fake()->randomElement(['不想要了', '拍错了']),
            'refund_amount'    => null,
            'description'      => fake()->text,
            'outer_refund_id'  => fake()->numerify('##########'),
        ];

        $data = array_merge($data, $merge);
        return RefundCreateCommand::from($data);
    }


    public function fakeOrderPayment(OrderPaymentData $data) : void
    {
        $data->paymentType      = 'online';
        $data->paymentId        = fake()->numberBetween(1000000, 999999999);
        $data->paymentMethod    = fake()->randomElement(['app', 'h5', 'mini-program', 'web', 'api']);
        $data->paymentChannel   = fake()->randomElement(['alipay', 'wechat', 'bank']);
        $data->paymentChannelNo = fake()->numerify('channel-no-########');
        $data->paymentTime      = date('Y-m-d H:i:s');
        $data->message          = fake()->randomElement(['ok', 'error']);
    }
}
