<?php

namespace RedJasmine\Promotion\Domain\Data;

use RedJasmine\Promotion\Domain\Models\Enums\ActivityOrderStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivityOrderData extends Data
{
    public UserInterface $user;
    public int           $activityId;
    public UserInterface $seller;
    public string        $productType;
    public int           $productId;


    public ?string    $orderNo        = null;
    public int        $quantity       = 1;
    public float      $amount;
    public ?\DateTime $participatedAt = null;


    #[WithCast(EnumCast::class, ActivityOrderStatusEnum::class)]
    public ActivityOrderStatusEnum $status = ActivityOrderStatusEnum::PARTICIPATED;
}