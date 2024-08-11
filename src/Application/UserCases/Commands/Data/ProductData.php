<?php

namespace RedJasmine\Shopping\Application\UserCases\Commands\Data;

use RedJasmine\Support\Data\Data;

class ProductData extends Data
{

    public int $productId;


    public int $skuId;
    /**
     * 商品件数
     * @var int
     */
    public int $num;

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
    public ?array  $buyerExpands;
    public ?array  $tools;


}
