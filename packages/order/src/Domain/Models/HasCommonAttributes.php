<?php

namespace RedJasmine\Order\Domain\Models;

use Cknow\Money\Money;
use RedJasmine\Order\Domain\Models\Casts\MoneyCast;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Casts\CurrencyCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;

/**
 * @property OrderTypeEnum $order_type
 * @property string $app_id
 * @property UserInterface $buyer
 * @property UserInterface $seller
 * @property UserInterface $store
 * @property UserInterface $guide
 * @property UserInterface $channel
 * @property UserInterface $source
 * @property Money $freight_amount
 */
trait HasCommonAttributes
{


    protected function getCommonAttributesCast() : array
    {
        return [
            'guide'                   => UserInterfaceCast::class.':1',
            'store'                   => UserInterfaceCast::class.':1',
            'channel'                 => UserInterfaceCast::class.':1',
            'source'                  => UserInterfaceCast::class,
            'currency'                => CurrencyCast::class,
            'price'                   => MoneyCast::class,
            'total_price'             => MoneyCast::class,
            'discount_amount'         => MoneyCast::class,
            'product_amount'          => MoneyCast::class,
            'tax_amount'              => MoneyCast::class,
            'service_amount'          => MoneyCast::class,
            'freight_amount'          => MoneyCast::class,
            'divided_discount_amount' => MoneyCast::class,
            'payable_amount'          => MoneyCast::class,
            'payment_amount'          => MoneyCast::class,
            'refund_amount'           => MoneyCast::class,
            'cost_price'              => MoneyCast::class,
            'total_cost_price'        => MoneyCast::class,


        ];
    }
}