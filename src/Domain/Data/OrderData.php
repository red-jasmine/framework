<?php

namespace RedJasmine\Shopping\Domain\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;

class OrderData extends Data
{

    /**
     * 买家
     * @var UserData
     */
    public UserData $seller;
    /**
     * 商品应付金额
     * @var Amount
     */
    public Amount $productPayableAmount;

    /**
     * 运费
     * @var Amount
     */
    public Amount $freightAmount;
    /**
     * 订单优惠
     * @var Amount
     */
    public Amount $discountAmount;
    /**
     * 订单应付金额
     * @var Amount
     */
    public Amount $payableAmount;


    /**
     * 商品集合
     * @var Collection
     */
    public Collection $products;


}
