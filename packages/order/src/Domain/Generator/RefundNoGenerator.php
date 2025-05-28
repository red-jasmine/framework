<?php

namespace RedJasmine\Order\Domain\Generator;

use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;
use RedJasmine\Support\Helpers\ID\NoCheckNumber;

class RefundNoGenerator implements RefundNoGeneratorInterface
{


    public function getBusinessCode() : string
    {
        return '88';
    }

    /**
     * @param  Refund  $model
     *
     * @return string
     */
    public function generator(Refund $model) : string
    {
        // 14位日期时间 + 10位序号  + 2 位业务 + 2位应用ID + 2位 卖家 + 2位 用户ID + 校验码

        $baseNo = implode('', [
            DatetimeIdGenerator::buildId(),
            $this->remainder($model->app_id),
            $this->remainder($model->seller_id),
            $this->remainder($model->buyer_id),
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
            'random'        => substr($UniqueId, -2, -1),
            'check_number'  => substr($UniqueId, -1, 0),
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