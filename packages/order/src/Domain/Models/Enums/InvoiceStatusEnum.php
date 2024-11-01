<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum InvoiceStatusEnum: string
{

    use EnumsHelper;

    case Invoicing = 'invoicing';

    case Invoiced = 'Invoiced';


    public static function labels():array
    {
        return  [
            self::Invoiced->value => __('red-jasmine-order::order.enums.invoice_status.invoicing'),
            self::Invoiced->value => __('red-jasmine-order::order.enums.invoice_status.invoiced'),
        ];

   }
}
