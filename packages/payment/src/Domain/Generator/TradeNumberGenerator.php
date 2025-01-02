<?php

namespace RedJasmine\Payment\Domain\Generator;

use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;

class TradeNumberGenerator implements TradeNumberGeneratorInterface
{
    /**
     * @param  array{merchant_id:int,merchant_app_id:int}  $factors
     *
     * @return string
     */
    public function generator(array $factors = []) : string
    {
        // 24位 + 4 位业务 + 2位 商户号 + 2位 应用ID
        return implode('', [
            DatetimeIdGenerator::buildId(),// 排序在高位
            '1111', // TODO 暂时
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
