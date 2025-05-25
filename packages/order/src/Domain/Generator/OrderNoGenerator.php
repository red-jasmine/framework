<?php

namespace RedJasmine\Order\Domain\Generator;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;

class OrderNoGenerator implements OrderNoGeneratorInterface
{


    public function getBusinessCode() : string
    {
        return '10';
    }

    /**
     * @param  Order  $order
     *
     * @return string
     */
    public function generator(Order $order) : string
    {
        // 14位时间 + 10位序号  + 2 位业务 + 2位应用ID + 2位 卖家 + 2位 用户ID

        return implode('', [
            DatetimeIdGenerator::buildId(),
            $this->getBusinessCode(),
            $this->remainder($order->app_id),
            $this->remainder($order->seller_id),
            $this->remainder($order->buyer_id),
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