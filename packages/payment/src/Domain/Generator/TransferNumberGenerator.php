<?php

namespace RedJasmine\Payment\Domain\Generator;

class TransferNumberGenerator extends AbstractPaymentNumberGenerator implements TransferNumberGeneratorInterface
{
    public function getBusinessCode() : string
    {
        return '1111';
    }


}
