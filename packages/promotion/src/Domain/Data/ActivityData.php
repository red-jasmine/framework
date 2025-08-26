<?php

namespace RedJasmine\Promotion\Domain\Data;

use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;
use RedJasmine\Promotion\Domain\Models\ValueObjects\ActivityRules;
use RedJasmine\Promotion\Domain\Models\ValueObjects\ProductRequirements;
use RedJasmine\Promotion\Domain\Models\ValueObjects\ShopRequirements;
use RedJasmine\Promotion\Domain\Models\ValueObjects\UserRequirements;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ActivityData extends Data
{
    public UserInterface $owner;
    public string $title;
    public ?string $description = null;
    
    #[WithCast(EnumCast::class, ActivityTypeEnum::class)]
    public ActivityTypeEnum $type;
    

    public ?\DateTime $signUpStartTime = null;
    public ?\DateTime $signUpEndTime = null;
    public \DateTime $startTime;
    public \DateTime $endTime;
    
    public ?ProductRequirements $productRequirements = null;
    public ?ShopRequirements $shopRequirements = null;
    public ?UserRequirements $userRequirements = null;
    
    public ?ActivityRules $rules = null;
    public ?array $overlayRules = null;
    
    #[WithCast(EnumCast::class, ActivityStatusEnum::class)]
    public ActivityStatusEnum $status = ActivityStatusEnum::DRAFT;
    
    public bool $isShow = true;
}