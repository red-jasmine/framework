<?php

namespace RedJasmine\Promotion\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Promotion\Domain\Models\Enums\ParticipationStatusEnum;
use RedJasmine\Promotion\Domain\Events\UserParticipatedEvent;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ActivityParticipation extends Model implements OperatorInterface
{
    use HasSnowflakeId;
    use HasOperator;
    use SoftDeletes;

    public $incrementing = false;
    
    protected $table = 'promotion_activity_participations';

    protected $fillable = [
        'activity_id',
        'product_id',
        'sku_id',
        'user_type',
        'user_id',
        'user_nickname',
        'order_no',
        'quantity',
        'amount',
        'participated_at',
        'activity_price',
        'discount_rate',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'activity_id' => 'integer',
            'product_id' => 'integer',
            'sku_id' => 'integer',
            'quantity' => 'integer',
            'amount' => 'decimal:2',
            'participated_at' => 'datetime',
            'activity_price' => 'decimal:2',
            'discount_rate' => 'decimal:2',
            'status' => ParticipationStatusEnum::class,
        ];
    }

    protected $dispatchesEvents = [
        'created' => UserParticipatedEvent::class,
    ];

    /**
     * 关联活动
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * 获取用户
     */
    public function getUser(): ?UserInterface
    {
        if (!$this->user_type || !$this->user_id) {
            return null;
        }
        
        $userClass = $this->user_type;
        if (!class_exists($userClass)) {
            return null;
        }
        
        return $userClass::find($this->user_id);
    }

    /**
     * 设置用户
     */
    public function setUser(UserInterface $user): void
    {
        $this->user_type = get_class($user);
        $this->user_id = $user->getKey();
        $this->user_nickname = $user->nickname ?? $user->name ?? null;
    }

    /**
     * 标记为已下单
     */
    public function markAsOrdered(string $orderNo): bool
    {
        $this->status = ParticipationStatusEnum::ORDERED;
        $this->order_no = $orderNo;
        return $this->save();
    }

    /**
     * 标记为已完成
     */
    public function markAsCompleted(): bool
    {
        $this->status = ParticipationStatusEnum::COMPLETED;
        return $this->save();
    }

    /**
     * 标记为已取消
     */
    public function markAsCancelled(): bool
    {
        $this->status = ParticipationStatusEnum::CANCELLED;
        return $this->save();
    }
}