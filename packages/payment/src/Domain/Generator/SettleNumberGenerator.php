<?php

namespace RedJasmine\Payment\Domain\Generator;

class SettleNumberGenerator extends AbstractPaymentNumberGenerator implements SettleNumberGeneratorInterface
{
    public function getBusinessCode() : string
    {
        return '6666';
    }


}
