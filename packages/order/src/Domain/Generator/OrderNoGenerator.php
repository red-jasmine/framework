<?php

namespace RedJasmine\Order\Domain\Generator;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;
use RedJasmine\Support\Helpers\ID\NoCheckNumber;

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
        // 14位日期时间 + 10位序号   + 2位应用ID + 2位 卖家 + 2位 用户ID + 2 位业务  + 1 随机 校验码

        // 14位日期时间 + 10位序号  + 2位应用ID + 2位 卖家 + 2位 用户ID  + 2 位业务  + 1 随机 + 1位 校验码

        $baseNo = implode('', [
            DatetimeIdGenerator::buildId(),
            $this->remainder($order->app_id),
            $this->remainder($order->seller_id),
            $this->remainder($order->buyer_id),
            $this->getBusinessCode(),// 业务识别码
            rand(0, 9)
        ]);
        return NoCheckNumber::generator($baseNo);

    }

    public function parse(string $UniqueId) : array
    {

        return [
            'datetime'      => substr($UniqueId, 0, 14),
            'app_id'        => substr($UniqueId, -10, -8),
            'seller_id'     => substr($UniqueId, -8, -6),
            'buyer_id'      => substr($UniqueId, -6, -4),
            'business_code' => substr($UniqueId, -4, -2),
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