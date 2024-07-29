<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Data;

use RedJasmine\Support\Data\Data;

class OrderProductInfoData extends Data
{
    public ?string $sellerRemarks;
    public ?string $sellerMessage;
    public ?string $buyerRemarks;
    public ?string $buyerMessage;
    public ?array  $buyerExtends;
    public ?array  $sellerExtends;
    public ?array  $otherExtends;
    public ?array  $tools;

}
