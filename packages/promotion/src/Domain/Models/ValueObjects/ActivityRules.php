<?php

namespace RedJasmine\Promotion\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class ActivityRules extends ValueObject
{
    public ?int $userParticipationLimit = null;
    public ?int $productPurchaseLimit = null;
    public bool $allowOverlay = false;
    public bool $newUserOnly = false;
    public bool $memberOnly = false;
    public ?array $userLevels = null;
    public ?array $regions = null;
    public ?array $excludeUsers = null;
    
    public function __construct(
        ?int $userParticipationLimit = null,
        ?int $productPurchaseLimit = null,
        bool $allowOverlay = false,
        bool $newUserOnly = false,
        bool $memberOnly = false,
        ?array $userLevels = null,
        ?array $regions = null,
        ?array $excludeUsers = null
    ) {
        $this->userParticipationLimit = $userParticipationLimit;
        $this->productPurchaseLimit = $productPurchaseLimit;
        $this->allowOverlay = $allowOverlay;
        $this->newUserOnly = $newUserOnly;
        $this->memberOnly = $memberOnly;
        $this->userLevels = $userLevels;
        $this->regions = $regions;
        $this->excludeUsers = $excludeUsers;
    }
    
    public function toArray(): array
    {
        return [
            'user_participation_limit' => $this->userParticipationLimit,
            'product_purchase_limit' => $this->productPurchaseLimit,
            'allow_overlay' => $this->allowOverlay,
            'new_user_only' => $this->newUserOnly,
            'member_only' => $this->memberOnly,
            'user_levels' => $this->userLevels,
            'regions' => $this->regions,
            'exclude_users' => $this->excludeUsers,
        ];
    }
}