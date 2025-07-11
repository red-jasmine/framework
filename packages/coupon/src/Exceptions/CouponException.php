<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class CouponException extends AbstractException
{
    protected $code = 400;
    protected $message = '优惠券操作失败';
} 