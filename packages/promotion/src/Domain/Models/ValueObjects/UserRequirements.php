<?php

namespace RedJasmine\Promotion\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class UserRequirements extends ValueObject
{
    public ?array $userLevels = null;
    public ?array $memberTypes = null;
    public ?array $regions = null;
    public ?int $minAge = null;
    public ?int $maxAge = null;
    public ?string $gender = null;
    public ?int $minOrderCount = null;
    public ?float $minSpendAmount = null;
    public ?array $excludeUsers = null;
    public bool $newUserOnly = false;
    public bool $memberOnly = false;
    
    public function __construct(
        ?array $userLevels = null,
        ?array $memberTypes = null,
        ?array $regions = null,
        ?int $minAge = null,
        ?int $maxAge = null,
        ?string $gender = null,
        ?int $minOrderCount = null,
        ?float $minSpendAmount = null,
        ?array $excludeUsers = null,
        bool $newUserOnly = false,
        bool $memberOnly = false
    ) {
        $this->userLevels = $userLevels;
        $this->memberTypes = $memberTypes;
        $this->regions = $regions;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
        $this->gender = $gender;
        $this->minOrderCount = $minOrderCount;
        $this->minSpendAmount = $minSpendAmount;
        $this->excludeUsers = $excludeUsers;
        $this->newUserOnly = $newUserOnly;
        $this->memberOnly = $memberOnly;
    }
    
    public function toArray(): array
    {
        return [
            'user_levels' => $this->userLevels,
            'member_types' => $this->memberTypes,
            'regions' => $this->regions,
            'min_age' => $this->minAge,
            'max_age' => $this->maxAge,
            'gender' => $this->gender,
            'min_order_count' => $this->minOrderCount,
            'min_spend_amount' => $this->minSpendAmount,
            'exclude_users' => $this->excludeUsers,
            'new_user_only' => $this->newUserOnly,
            'member_only' => $this->memberOnly,
        ];
    }
}