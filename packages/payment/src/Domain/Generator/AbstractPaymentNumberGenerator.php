<?php

namespace RedJasmine\Payment\Domain\Generator;

use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;

abstract class AbstractPaymentNumberGenerator
{


    abstract public function getBusinessCode() : string;

    /**
     * @param  array{merchant_id:int,merchant_app_id:int}  $factors
     *
     * @return string
     */
    public function generator(array $factors = []) : string
    {
        // 24位 + 4 位业务 + 2位 商户号 + 2位 应用ID
        // 14位时间YYYYMMDDHHIISS + 10位序号  +  + 2位 商户号 + 2位 应用ID
        return implode('', [
            DatetimeIdGenerator::buildId(),
            $this->getBusinessCode(),
            $this->remainder($factors['merchant_id']),
            $this->remainder($factors['merchant_app_id']),
        ]);
    }

    public function parse(string $UniqueId) : array
    {
        return [
            'datetime'                  => substr($UniqueId, 0, 14),
            'merchant_id_remainder'     => substr($UniqueId, -4, -2),
            'merchant_app_id_remainder' => substr($UniqueId, -2),

        ];
    }

    protected function remainder(int $number) : string
    {
        return sprintf("%02d", ($number % 64));
    }


}
