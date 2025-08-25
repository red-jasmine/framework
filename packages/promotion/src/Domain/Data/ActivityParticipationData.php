<?php

namespace RedJasmine\Promotion\Domain\Data;

use RedJasmine\Promotion\Domain\Models\Enums\ParticipationStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivityParticipationData extends Data
{
    public UserInterface $owner;
    public int $activityId;
    public int $productId;
    public ?int $skuId = null;
    
    public UserInterface $user;
    
    public ?string $orderNo = null;
    public int $quantity = 1;
    public float $amount;
    public ?\DateTime $participatedAt = null;
    
    public ?float $activityPrice = null;
    public ?float $discountRate = null;
    
    #[WithCast(EnumCast::class, ParticipationStatusEnum::class)]
    public ParticipationStatusEnum $status = ParticipationStatusEnum::PARTICIPATED;
}