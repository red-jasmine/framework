<?php

namespace RedJasmine\Promotion\Application\Services\Queries;

use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 活动列表查询
 */
class ActivityListQuery extends PaginateQuery
{
    public ?string $title = null;
    
    #[WithCast(EnumCast::class, ActivityTypeEnum::class)]
    public ?ActivityTypeEnum $type = null;
    
    #[WithCast(EnumCast::class, ActivityStatusEnum::class)]
    public ?ActivityStatusEnum $status = null;
    
    public ?bool $isShow = null;
    public ?int $categoryId = null;
    public ?int $productId = null;
    public ?string $startTimeFrom = null;
    public ?string $startTimeTo = null;
    public ?string $endTimeFrom = null;
    public ?string $endTimeTo = null;
    public ?bool $runningOnly = null;
    public ?bool $upcomingOnly = null;
}
