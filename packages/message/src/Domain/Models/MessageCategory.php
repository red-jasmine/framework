<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Message\Domain\Models\Enums\BizEnum;
use RedJasmine\Message\Domain\Models\Enums\StatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

/**
 * 消息分类聚合根
 */
class MessageCategory extends Model
{
    use HasOwner;
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $fillable = [
        'biz',
        'name',
        'description',
        'icon',
        'color',
        'sort',
        'status',
        'owner_id',
    ];

    protected $casts = [
        'biz' => BizEnum::class,
        'status' => StatusEnum::class,
        'sort' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // 分类创建时的业务规则
        static::creating(function (self $category) {
            $category->validateCreation();
        });

        // 分类更新时的业务规则
        static::updating(function (self $category) {
            $category->validateUpdate();
        });

        // 分类删除时的业务规则
        static::deleting(function (self $category) {
            $category->validateDeletion();
        });
    }

    /**
     * 消息关联
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'category_id');
    }

    /**
     * 启用分类
     */
    public function enable(): void
    {
        if ($this->status === StatusEnum::ENABLE) {
            return;
        }

        $this->status = StatusEnum::ENABLE;
        $this->save();

        // 发布分类启用事件
        $this->dispatchCategoryEnabledEvent();
    }

    /**
     * 禁用分类
     */
    public function disable(): void
    {
        if ($this->status === StatusEnum::DISABLE) {
            return;
        }

        $this->status = StatusEnum::DISABLE;
        $this->save();

        // 发布分类禁用事件
        $this->dispatchCategoryDisabledEvent();
    }

    /**
     * 是否启用
     */
    public function isEnabled(): bool
    {
        return $this->status === StatusEnum::ENABLE;
    }

    /**
     * 是否禁用
     */
    public function isDisabled(): bool
    {
        return $this->status === StatusEnum::DISABLE;
    }

    /**
     * 调整排序
     */
    public function updateSort(int $sort): void
    {
        $this->sort = $sort;
        $this->save();
    }

    /**
     * 获取消息数量
     */
    public function getMessageCount(): int
    {
        return $this->messages()->count();
    }

    /**
     * 获取未读消息数量
     */
    public function getUnreadMessageCount(): int
    {
        return $this->messages()->unread()->count();
    }

    /**
     * 是否可以删除
     */
    public function canDelete(): bool
    {
        return $this->getMessageCount() === 0;
    }

    /**
     * 验证分类创建
     */
    protected function validateCreation(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('分类名称不能为空');
        }

        if (mb_strlen($this->name) > 100) {
            throw new \InvalidArgumentException('分类名称长度不能超过100个字符');
        }

        // 验证名称唯一性
        $this->validateNameUniqueness();
    }

    /**
     * 验证分类更新
     */
    protected function validateUpdate(): void
    {
        if ($this->isDirty('name')) {
            if (empty($this->name)) {
                throw new \InvalidArgumentException('分类名称不能为空');
            }

            if (mb_strlen($this->name) > 100) {
                throw new \InvalidArgumentException('分类名称长度不能超过100个字符');
            }

            // 验证名称唯一性
            $this->validateNameUniqueness();
        }
    }

    /**
     * 验证分类删除
     */
    protected function validateDeletion(): void
    {
        if (!$this->canDelete()) {
            throw new \InvalidArgumentException('该分类下还有消息，不能删除');
        }
    }

    /**
     * 验证名称唯一性
     */
    protected function validateNameUniqueness(): void
    {
        $query = static::where('owner_id', $this->owner_id)
            ->where('biz', $this->biz)
            ->where('name', $this->name);

        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        if ($query->exists()) {
            throw new \InvalidArgumentException('分类名称在当前业务线下已存在');
        }
    }

    /**
     * 发布分类启用事件
     */
    protected function dispatchCategoryEnabledEvent(): void
    {
        // 这里可以发布领域事件
        // event(new MessageCategoryEnabledEvent($this));
    }

    /**
     * 发布分类禁用事件
     */
    protected function dispatchCategoryDisabledEvent(): void
    {
        // 这里可以发布领域事件
        // event(new MessageCategoryDisabledEvent($this));
    }

    /**
     * 查询作用域：启用的分类
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', StatusEnum::ENABLE);
    }

    /**
     * 查询作用域：禁用的分类
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', StatusEnum::DISABLE);
    }

    /**
     * 查询作用域：按业务线查询
     */
    public function scopeForBiz($query, BizEnum $biz)
    {
        return $query->where('biz', $biz);
    }

    /**
     * 查询作用域：按排序查询
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
