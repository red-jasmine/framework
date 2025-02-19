<?php

namespace RedJasmine\Order\Domain\Generator;

use RedJasmine\Support\Domain\Generator\UniqueIdGeneratorInterface;
use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;

class OrderNoGenerator implements UniqueIdGeneratorInterface
{


    public function getBusinessCode() : string
    {
        return '10';
    }

    /**
     * @param  array{app_id:string,seller_id:int,buyer_id:int}  $factors
     *
     * @return string
     */
    public function generator(array $factors = []) : string
    {
        // 14位时间 + 10位序号  + 2 位业务 + 2位应用ID + 2位 卖家 + 2位 用户ID

        return implode('', [
            DatetimeIdGenerator::buildId(),
            $this->getBusinessCode(),
            $this->remainder($factors['app_id']),
            $this->remainder($factors['seller_id']),
            $this->remainder($factors['buyer_id']),
        ]);
    }

    public function parse(string $UniqueId) : array
    {
        return [
            'datetime'  => substr($UniqueId, 0, 14),
            'seller_id' => substr($UniqueId, -6, -4),
            'seller_id' => substr($UniqueId, -4, -2),
            'buyer_id'  => substr($UniqueId, -2),
        ];
    }

    protected function remainder(int|string $number) : string
    {

        if (is_string($number)) {
            $number = crc32($number);
        }
        return sprintf("%02d", ($number % 64));
    }


}