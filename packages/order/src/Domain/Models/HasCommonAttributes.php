<?php

namespace RedJasmine\Order\Domain\Models;

use RedJasmine\Money\Data\Money;
use RedJasmine\Money\Casts\CurrencyCast;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Casts\UserInterfaceCast;

/**
 * @property OrderTypeEnum $order_type
 * @property string $biz
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
            'price'                   => MoneyCast::class.':currency,price,1',
            'total_price'             => MoneyCast::class.':currency,total_price,1',
            'discount_amount'         => MoneyCast::class.':currency,discount_amount,1',
            'product_amount'          => MoneyCast::class.':currency,product_amount,1',
            'tax_amount'              => MoneyCast::class.':currency,tax_amount,1',
            'service_amount'          => MoneyCast::class.':currency,service_amount,1',
            'freight_amount'          => MoneyCast::class.':currency,freight_amount,1',
            'divided_discount_amount' => MoneyCast::class.':currency,divided_discount_amount,1',
            'payable_amount'          => MoneyCast::class.':currency,payable_amount,1',
            'payment_amount'          => MoneyCast::class.':currency,payment_amount,1',
            'refund_amount'           => MoneyCast::class.':currency,refund_amount,1',


            'cost_price'       => MoneyCast::class.':currency,cost_price,1',
            'total_cost_price' => MoneyCast::class.':currency,total_cost_price,1',
            
        ];
    }
}