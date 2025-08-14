<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;
use RedJasmine\Message\Domain\Models\Enums\BizEnum;
use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageSourceEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\PushStatusEnum;
use RedJasmine\Message\Domain\Models\ValueObjects\MessageContent;
use RedJasmine\Message\Domain\Models\ValueObjects\MessageData;
use RedJasmine\Message\Domain\Models\ValueObjects\PushConfig;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 消息聚合根
 */
class Message extends Model
{
    use HasSnowflakeId;
    use HasOwner;
    use HasDateTimeFormatter;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'biz',
        'category_id',
        'receiver_id',
        'sender_id',
        'template_id',
        'title',
        'content',
        'data',
        'source',
        'type',
        'priority',
        'status',
        'read_at',
        'channels',
        'push_status',
        'is_urgent',
        'is_burn_after_read',
        'expires_at',
        'owner_id',
        'operator_id',
    ];

    protected $casts = [
        'type'               => MessageTypeEnum::class,
        'priority'           => MessagePriorityEnum::class,
        'status'             => MessageStatusEnum::class,
        'push_status'        => PushStatusEnum::class,
        'data'               => 'array',
        'channels'           => 'array',
        'is_urgent'          => 'boolean',
        'is_burn_after_read' => 'boolean',
        'read_at'            => 'datetime',
        'expires_at'         => 'datetime',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
        'deleted_at'         => 'datetime',
    ];

    protected static function boot() : void
    {
        parent::boot();

        // 消息创建时的业务规则
        static::creating(function (self $message) {
            $message->validateCreation();
        });

        // 消息更新时的业务规则
        static::updating(function (self $message) {
            $message->validateUpdate();
        });

        // 消息删除时的业务规则
        static::deleting(function (self $message) {
            $message->validateDeletion();
        });
    }

    /**
     * 消息分类关联
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(MessageCategory::class, 'category_id');
    }

    /**
     * 消息模板关联
     */
    public function template() : BelongsTo
    {
        return $this->belongsTo(MessageTemplate::class, 'template_id');
    }

    /**
     * 推送日志关联
     */
    public function pushLogs() : HasMany
    {
        return $this->hasMany(MessagePushLog::class, 'message_id');
    }

    /**
     * 获取消息内容值对象
     */
    public function getMessageContent() : MessageContent
    {
        return new MessageContent(
            title: $this->title,
            content: $this->content,
            contentType: 'text', // 可以从扩展数据中获取
            attachments: $this->getMessageData()->getExtension('attachments', [])
        );
    }

    /**
     * 获取推送配置值对象
     */
    public function getPushConfig() : PushConfig
    {
        return new PushConfig(
            channels: $this->channels ?? [],
            parameters: $this->getMessageData()->getExtension('push_parameters', []),
            retryConfig: $this->getMessageData()->getExtension('retry_config', []),
            immediate: $this->getMessageData()->getExtension('immediate', true),
            delaySeconds: $this->getMessageData()->getExtension('delay_seconds')
        );
    }

    /**
     * 获取消息数据值对象
     */
    public function getMessageData() : MessageData
    {
        $data = $this->data ?? [];

        return new MessageData(
            businessData: $data['business_data'] ?? [],
            templateVariables: $data['template_variables'] ?? [],
            extensions: $data['extensions'] ?? []
        );
    }

    /**
     * 标记消息为已读
     */
    public function markAsRead(UserInterface $reader) : void
    {
        $this->validateCanRead($reader);

        if ($this->status === MessageStatusEnum::READ) {
            return; // 已经是已读状态
        }

        $this->status  = MessageStatusEnum::READ;
        $this->read_at = now();

        $this->save();

        // 如果是阅后即焚消息，立即删除
        if ($this->is_burn_after_read) {
            $this->delete();
        }

        // 发布消息已读事件
        $this->dispatchMessageReadEvent($reader);
    }

    /**
     * 归档消息
     */
    public function archive() : void
    {
        if ($this->status === MessageStatusEnum::ARCHIVED) {
            return; // 已经是归档状态
        }

        $this->status = MessageStatusEnum::ARCHIVED;
        $this->save();

        // 发布消息归档事件
        $this->dispatchMessageArchivedEvent();
    }

    /**
     * 检查消息是否过期
     */
    public function isExpired() : bool
    {
        if ($this->expires_at === null) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * 检查用户是否可以访问消息
     */
    public function canAccess(UserInterface $user) : bool
    {
        // 检查接收人权限
        if ($this->receiver_id === (string) $user->getKey()) {
            return true;
        }

        // 检查管理员权限（可以扩展）
        return false;
    }

    /**
     * 检查消息是否可以阅读
     */
    public function canRead(UserInterface $user) : bool
    {
        if (!$this->canAccess($user)) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        return $this->status !== MessageStatusEnum::ARCHIVED;
    }

    /**
     * 更新推送状态
     */
    public function updatePushStatus(PushStatusEnum $status) : void
    {
        $this->push_status = $status;
        $this->save();
    }

    /**
     * 获取优先级数值
     */
    public function getPriorityValue() : int
    {
        return $this->priority->getPriorityValue();
    }

    /**
     * 是否为高优先级消息
     */
    public function isHighPriority() : bool
    {
        return in_array($this->priority, [MessagePriorityEnum::HIGH, MessagePriorityEnum::URGENT], true);
    }

    /**
     * 是否为紧急消息
     */
    public function isUrgent() : bool
    {
        return $this->priority === MessagePriorityEnum::URGENT || $this->is_urgent;
    }

    /**
     * 验证消息创建
     */
    protected function validateCreation() : void
    {
        // 验证必填字段
        if (empty($this->title)) {
            throw new InvalidArgumentException('消息标题不能为空');
        }

        if (empty($this->content)) {
            throw new InvalidArgumentException('消息内容不能为空');
        }

        if (empty($this->receiver_id)) {
            throw new InvalidArgumentException('接收人不能为空');
        }

        // 验证过期时间
        if ($this->expires_at && $this->expires_at->isPast()) {
            throw new InvalidArgumentException('过期时间不能早于当前时间');
        }

        // 验证分类权限
        if ($this->category_id) {
            $this->validateCategoryAccess();
        }
    }

    /**
     * 验证消息更新
     */
    protected function validateUpdate() : void
    {
        // 消息内容不可修改（业务规则）
        if ($this->isDirty(['title', 'content']) && $this->exists) {
            throw new InvalidArgumentException('消息内容创建后不可修改');
        }

        // 已归档的消息不能修改状态
        if ($this->getOriginal('status') === MessageStatusEnum::ARCHIVED->value &&
            $this->isDirty('status')) {
            throw new InvalidArgumentException('已归档的消息不能修改状态');
        }
    }

    /**
     * 验证消息删除
     */
    protected function validateDeletion() : void
    {
        // 可以添加删除前的业务规则验证
    }

    /**
     * 验证用户是否可以阅读消息
     */
    protected function validateCanRead(UserInterface $reader) : void
    {
        if (!$this->canRead($reader)) {
            throw new InvalidArgumentException('没有权限阅读此消息');
        }
    }

    /**
     * 验证分类访问权限
     */
    protected function validateCategoryAccess() : void
    {
        if (!$this->category) {
            return;
        }

        if (!$this->category->isEnabled()) {
            throw new InvalidArgumentException('消息分类已被禁用');
        }
    }

    /**
     * 发布消息已读事件
     */
    protected function dispatchMessageReadEvent(UserInterface $reader) : void
    {
        // 这里可以发布领域事件
        // event(new MessageReadEvent($this, $reader));
    }

    /**
     * 发布消息归档事件
     */
    protected function dispatchMessageArchivedEvent() : void
    {
        // 这里可以发布领域事件
        // event(new MessageArchivedEvent($this));
    }

    /**
     * 查询作用域：根据接收人查询
     */
    public function scopeForReceiver($query, string $receiverId)
    {
        return $query->where('receiver_id', $receiverId);
    }

    /**
     * 查询作用域：根据状态查询
     */
    public function scopeWithStatus($query, MessageStatusEnum $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 查询作用域：未读消息
     */
    public function scopeUnread($query)
    {
        return $query->where('status', MessageStatusEnum::UNREAD);
    }

    /**
     * 查询作用域：已读消息
     */
    public function scopeRead($query)
    {
        return $query->where('status', MessageStatusEnum::READ);
    }

    /**
     * 查询作用域：未过期的消息
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * 查询作用域：高优先级消息
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [MessagePriorityEnum::HIGH, MessagePriorityEnum::URGENT]);
    }

    /**
     * 查询作用域：紧急消息
     */
    public function scopeUrgent($query)
    {
        return $query->where(function ($q) {
            $q->where('priority', MessagePriorityEnum::URGENT)
              ->orWhere('is_urgent', true);
        });
    }

    /**
     * 查询作用域：按业务线查询
     */
    public function scopeForBiz($query, BizEnum $biz)
    {
        return $query->where('biz', $biz);
    }
}
