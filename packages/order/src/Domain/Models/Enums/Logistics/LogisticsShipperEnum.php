<?php

namespace RedJasmine\Order\Domain\Models\Enums\Logistics;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum LogisticsShipperEnum: string
{

    use EnumsHelper;

    case SELLER = 'seller';
    case BUYER = 'buyer';


    public static function labels() : array
    {
        return [
            self::SELLER->value => __('red-jasmine-order::logistics.enums.shipper.seller'),
            self::BUYER->value  => __('red-jasmine-order::logistics.enums.shipper.buyer'),
        ];
    }

}
