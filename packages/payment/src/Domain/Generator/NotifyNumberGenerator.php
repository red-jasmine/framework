<?php

namespace RedJasmine\Payment\Domain\Generator;

class NotifyNumberGenerator extends AbstractPaymentNumberGenerator implements RefundNumberGeneratorInterface
{
    public function getBusinessCode() : string
    {
        return '3333';
    }


}
