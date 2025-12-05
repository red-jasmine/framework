<?php

namespace RedJasmine\Announcement\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;
use RedJasmine\Announcement\Domain\Models\Enums\AnnouncementStatus;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Contracts\OwnerInterface;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasApproval;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class Announcement extends Model implements OperatorInterface, OwnerInterface
{
    use HasSnowflakeId;
    use HasOwner;
    use HasOperator;
    use SoftDeletes;
    use HasApproval;

    public $incrementing = false;

    protected $fillable = [
        'biz',
        'owner_type',
        'owner_id',
        'category_id',
        'title',
        'cover',
        'content',
        'scopes',
        'channels',
        'publish_time',
        'status',
        'attachments',
        'approval_status',
        'is_force_read',
    ];

    protected $casts = [
        'scopes'          => 'array',
        'channels'        => 'array',
        'attachments'     => 'array',
        'publish_time'    => 'datetime',
        'status'          => AnnouncementStatus::class,
        'approval_status' => ApprovalStatusEnum::class,
        'approval_time'   => 'timestamp',
        'is_force_read'   => 'boolean',
    ];

    protected static function boot() : void
    {
        parent::boot();

        // 生命周期钩子
        static::saving(function ($model) {
            // 保存时的业务逻辑
            if ($model->isDirty('status') && $model->status === AnnouncementStatus::PUBLISHED) {
                $model->publish_time = now();
            }
        });
    }

    /**
     * 关联分类
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(AnnouncementCategory::class, 'category_id');
    }


    /**
     * 发布公告
     */
    public function publish() : void
    {
        if (!$this->canPublish()) {
            throw new InvalidArgumentException('只有已审批通过的公告才能发布');
        }

        $this->status       = AnnouncementStatus::PUBLISHED;
        $this->publish_time = now();

    }

    /**
     * 撤销公告
     */
    public function revoke() : void
    {
        if ($this->status !== AnnouncementStatus::PUBLISHED) {
            throw new InvalidArgumentException('只有已发布的公告才能撤销');
        }

        $this->status = AnnouncementStatus::REVOKED;

    }

    /**
     * 提交审批
     */
    public function submitForApproval() : void
    {
        if ($this->status !== AnnouncementStatus::DRAFT) {
            throw new InvalidArgumentException('只有草稿状态的公告才能提交审批');
        }

        $this->approval_status = ApprovalStatusEnum::PENDING;

    }


    /**
     * 检查是否可以编辑
     */
    public function canEdit() : bool
    {
        return in_array($this->status, [
            AnnouncementStatus::DRAFT,
            AnnouncementStatus::REVOKED,
        ]);
    }

    /**
     * 检查是否可以发布
     */
    public function canPublish() : bool
    {
        return $this->approval_status === ApprovalStatusEnum::PASS &&

               in_array($this->status, [
                   AnnouncementStatus::DRAFT,
                   AnnouncementStatus::REVOKED,
               ]);
    }

    /**
     * 检查是否可以撤销
     */
    public function canRevoke() : bool
    {
        return $this->status === AnnouncementStatus::PUBLISHED;
    }


}
