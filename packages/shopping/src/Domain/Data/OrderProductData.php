<?php

namespace RedJasmine\Shopping\Domain\Data;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;

class OrderProductData extends ProductPurchaseFactor
{
    /**
     * 外部单号
     * @var string|null
     */
    public ?string $outerOrderProductId = null;
    /**
     *
     * @var string|null
     */
    public ?string $buyerRemarks;
    public ?string $buyerMessage;
    public ?array  $buyerExtra;


    /**
     * 卡密查询信息
     * @var string|null
     */
    public ?string $contact  = null;
    public ?string $password = null;

}