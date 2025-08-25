<?php

namespace RedJasmine\Promotion\Domain\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;

class ActivityStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Activity $activity,
        public ActivityStatusEnum $oldStatus,
        public ActivityStatusEnum $newStatus
    ) {
    }
}