<?php

namespace RedJasmine\Distribution\Domain\Services\Conditions;

use RedJasmine\Support\Foundation\Manager\ServiceManager;

class PromoterConditionProviderManager extends ServiceManager
{
    protected const  PROVIDERS = [
        BuyProductCondition::NAME           => BuyProductCondition::class,
        PurchasedOrderAmountCondition::NAME => PurchasedOrderAmountCondition::class,
        PurchasedOrderCountCondition::NAME  => PurchasedOrderCountCondition::class,
        InviteUserCountCondition::NAME      => InviteUserCountCondition::class,
        InvitePromoterCountCondition::NAME  => InvitePromoterCountCondition::class,
        PromotionOrderAmountCondition::NAME => PromotionOrderAmountCondition::class,
    ];

}