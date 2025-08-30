<?php

namespace RedJasmine\Promotion\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;
use RedJasmine\Promotion\Domain\Events\ActivityCreatedEvent;
use RedJasmine\Promotion\Domain\Events\ActivityEndedEvent;
use RedJasmine\Promotion\Domain\Events\ActivityStartedEvent;
use RedJasmine\Promotion\Domain\Events\ActivityStatusChangedEvent;
use RedJasmine\Promotion\Domain\Models\ValueObjects\ActivityRules;
use RedJasmine\Promotion\Domain\Models\ValueObjects\ProductRequirements;
use RedJasmine\Promotion\Domain\Models\ValueObjects\ShopRequirements;
use RedJasmine\Promotion\Domain\Models\ValueObjects\UserRequirements;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Activity extends Model implements OwnerInterface, OperatorInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'promotion_activities';

    protected $fillable = [
        'title',
        'description',
        'type',
        'owner_type',
        'owner_id',
        'client_type',
        'client_id',
        'sign_up_start_time',
        'sign_up_end_time',
        'start_time',
        'end_time',
        'product_requirements',
        'shop_requirements',
        'user_requirements',
        'rules',
        'overlay_rules',
        'status',
        'is_show',
        'total_products',
        'total_orders',
        'total_sales',
        'total_participants',
    ];
    protected $dispatchesEvents = [
        'created' => ActivityCreatedEvent::class,
    ];

    protected static function boot() : void
    {
        parent::boot();

        static::saving(function (Activity $activity) {
            // 状态变更事件
            if ($activity->isDirty('status') && $activity->exists) {
                $oldStatus = ActivityStatusEnum::from($activity->getOriginal('status'));
                $newStatus = $activity->status;

                event(new ActivityStatusChangedEvent($activity, $oldStatus, $newStatus));

                // 触发特定状态事件
                if ($newStatus === ActivityStatusEnum::RUNNING) {
                    event(new ActivityStartedEvent($activity));
                } elseif ($newStatus === ActivityStatusEnum::ENDED) {
                    event(new ActivityEndedEvent($activity));
                }
            }
        });
    }

    /**
     * 关联活动商品
     */
    public function products() : HasMany
    {
        return $this->hasMany(ActivityProduct::class);
    }

    /**
     * 关联活动参与记录
     */
    public function participations() : HasMany
    {
        return $this->hasMany(ActivityOrder::class);
    }

    /**
     * 检查活动是否可以参与
     */
    public function canParticipate() : bool
    {
        return $this->status === ActivityStatusEnum::RUNNING
               && $this->is_show
               && $this->start_time <= now()
               && $this->end_time >= now();
    }

    /**
     * 检查活动是否在报名期内
     */
    public function isInSignUpPeriod() : bool
    {
        if (!$this->sign_up_start_time || !$this->sign_up_end_time) {
            return false;
        }

        $now = now();
        return $this->sign_up_start_time <= $now && $this->sign_up_end_time >= $now;
    }

    /**
     * 检查活动是否已开始
     */
    public function hasStarted() : bool
    {
        return $this->start_time <= now();
    }

    /**
     * 检查活动是否已结束
     */
    public function hasEnded() : bool
    {
        return $this->end_time < now();
    }

    protected function casts() : array
    {
        return [
            'type'                 => ActivityTypeEnum::class,
            'status'               => ActivityStatusEnum::class,
            'sign_up_start_time'   => 'datetime',
            'sign_up_end_time'     => 'datetime',
            'start_time'           => 'datetime',
            'end_time'             => 'datetime',
            'product_requirements' => ProductRequirements::class,
            'shop_requirements'    => ShopRequirements::class,
            'user_requirements'    => UserRequirements::class,
            'rules'                => ActivityRules::class,
            'overlay_rules'        => 'array',
            'is_show'              => 'boolean',
            'total_products'       => 'integer',
            'total_orders'         => 'integer',
            'total_sales'          => 'decimal:2',
            'total_participants'   => 'integer',
        ];
    }
}