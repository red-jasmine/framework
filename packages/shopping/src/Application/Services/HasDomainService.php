<?php

namespace RedJasmine\Shopping\Application\Services;

use RedJasmine\Shopping\Domain\Contracts\CouponServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PaymentServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;

trait HasDomainService
{


    public function makeDomainService($service)
    {

        return app($service, [
            'productService'   => app(ProductServiceInterface::class),
            'stockService'     => app(StockServiceInterface::class),
            'promotionService' => app(PromotionServiceInterface::class),
            'orderService'     => app(OrderServiceInterface::class),
            'couponService'    => app(CouponServiceInterface::class),
            'paymentService'   => app(PaymentServiceInterface::class),
        ]);
    }
}