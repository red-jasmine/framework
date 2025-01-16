<?php

namespace RedJasmine\Payment\Domain\Generator;

class TradeNumberGenerator extends AbstractPaymentNumberGenerator implements TradeNumberGeneratorInterface
{

    public function getBusinessCode() : string
    {
        return '1010';
    }


}
