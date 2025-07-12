<?php

namespace RedJasmine\Coupon\Domain\Models\Generator;

use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Support\Helpers\ID\DatetimeIdGenerator;
use RedJasmine\Support\Helpers\ID\NoCheckNumber;

class CouponNoGenerator
{


    public function getBusinessCode() : string
    {
        return rand(10, 99);
    }

    public function generator(UserCoupon $userCoupon) : string
    {


        $baseNo = implode('', [
            DatetimeIdGenerator::buildId(),
            $this->remainder($userCoupon->owner_id),
            $this->remainder($userCoupon->user_id),
            $this->getBusinessCode(),// 业务识别码
            rand(0, 9)
        ]);
        return NoCheckNumber::generator($baseNo);

    }

    public function parse(string $UniqueId) : array
    {

        return [
            'datetime'      => substr($UniqueId, 0, 14),
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