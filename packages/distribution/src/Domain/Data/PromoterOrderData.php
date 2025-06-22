<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Contracts\UserInterface;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class PromoterOrderData extends Data
{
    /**
     * 分销员ID
     */
    public int $promoterId;
    
    /**
     * 订单ID
     */
    public int $orderId;
    
    /**
     * 订单类型
     */
    public string $orderType;
    
    /**
     * 用户
     */
    public UserInterface $user;
    
    /**
     * 订单金额
     */
    public int $orderAmount = 0;
    
    /**
     * 佣金金额
     */
    public int $commissionAmount = 0;
    
    /**
     * 佣金比例
     */
    public int $commissionRatio = 0;
    
    /**
     * 订单时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public Carbon $orderTime;
    
    /**
     * 结算时间
     */
    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $settlementTime = null;
    
    /**
     * 备注
     */
    public ?string $remarks = null;
}