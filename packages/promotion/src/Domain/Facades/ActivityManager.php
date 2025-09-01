<?php

namespace RedJasmine\Promotion\Domain\Facades;

use Illuminate\Support\Facades\Facade;
use RedJasmine\Promotion\Application\Services\ActivityApplicationService;

/**
 * 活动管理门面
 * 
 * @method static \RedJasmine\Promotion\Domain\Contracts\ActivityTypeHandlerInterface getTypeHandler(string|\RedJasmine\Promotion\Domain\Models\Activity $activityOrType)
 * @method static void validateActivityData(\RedJasmine\Promotion\Domain\Data\ActivityData $data)
 * @method static array calculateActivityPrice(\RedJasmine\Promotion\Domain\Models\Activity $activity, int $productId, int $quantity = 1, array $context = [])
 * @method static \RedJasmine\Promotion\Domain\Models\ActivityOrder handleParticipation(\RedJasmine\Promotion\Domain\Models\Activity $activity, \RedJasmine\Support\Contracts\UserInterface $user, array $participationData)
 * @method static void startActivity(\RedJasmine\Promotion\Domain\Models\Activity $activity)
 * @method static void endActivity(\RedJasmine\Promotion\Domain\Models\Activity $activity)
 * @method static array getActivityTypeExtensionFields(string $activityType)
 * @method static array getActivityTypeDefaultRules(string $activityType)
 * @method static array getRegisteredActivityTypes()
 * @method static bool canParticipate(\RedJasmine\Promotion\Domain\Models\Activity $activity, \RedJasmine\Support\Contracts\UserInterface $user, array $participationData = [])
 * @method static string|null getParticipationFailureReason(\RedJasmine\Promotion\Domain\Models\Activity $activity, \RedJasmine\Support\Contracts\UserInterface $user, array $participationData = [])
 * @method static array calculateBatchActivityPrice(\RedJasmine\Promotion\Domain\Models\Activity $activity, array $items, array $context = [])
 * 
 * @see \RedJasmine\Promotion\Application\Services\ActivityApplicationService
 */
class ActivityManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityApplicationService::class;
    }
}
