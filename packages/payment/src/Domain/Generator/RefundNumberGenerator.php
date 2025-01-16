<?php

namespace RedJasmine\Payment\Domain\Generator;

class RefundNumberGenerator extends AbstractPaymentNumberGenerator implements RefundNumberGeneratorInterface
{

    public function getBusinessCode() : string
    {
        return '2222';
    }


}
