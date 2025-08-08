<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum InvoiceStatusEnum: string
{

    use EnumsHelper;

    case INVOICING = 'invoicing';

    case INVOICED = 'invoiced';


    public static function labels():array
    {
        return  [
            self::INVOICED->value => __('red-jasmine-order::order.enums.invoice_status.invoicing'),
            self::INVOICED->value => __('red-jasmine-order::order.enums.invoice_status.invoiced'),
        ];

   }
}
