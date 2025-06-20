<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Domain\Events\Promoter\PromoterApplied;
use RedJasmine\Distribution\Domain\Events\Promoter\PromoterAudited;
use RedJasmine\Distribution\Domain\Events\Promoter\PromoterDeleted;
use RedJasmine\Distribution\Domain\Events\Promoter\PromoterDisabled;
use RedJasmine\Distribution\Domain\Events\Promoter\PromoterDowngraded;
use RedJasmine\Distribution\Domain\Events\Promoter\PromoterEnabled;
use RedJasmine\Distribution\Domain\Events\Promoter\PromoterUpgraded;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 分销员
 * @property bool $isPromoter 是否推广员
 * @property int $level 推广等级
 * @property int $parentId 推广上级
 * @property string $name 名称
 * @property string|null $remarks 备注
 * @property PromoterStatusEnum $status 状态
 */
class Promoter extends Model implements OperatorInterface, OwnerInterface
{
    public $incrementing = false;

    use HasSnowflakeId;
    use HasOperator;
    use HasOwner;

    /**
     * 模型事件映射
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'applied'    => PromoterApplied::class,
        'audited'    => PromoterAudited::class,
        'upgraded'   => PromoterUpgraded::class,
        'downgraded' => PromoterDowngraded::class,
        'disabled'   => PromoterDisabled::class,
        'enabled'    => PromoterEnabled::class,
        'deleted'    => PromoterDeleted::class,
    ];

    protected function casts() : array
    {
        return [
            'status' => PromoterStatusEnum::class,
        ];
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);

        if (!$instance->exists) {
            $instance->setUniqueIds();

        }

        return $instance;
    }

    public function setOwner(UserInterface $owner) : static
    {
        $this->owner = $owner;

        return $this;;
    }


    /**
     * 设置分销员信息
     */
    public function setPromoterInfo(string $name, ?string $remarks = null) : self
    {
        $this->name    = $name;
        $this->remarks = $remarks;
        return $this;
    }

    /**
     * 设置上级
     */
    public function setParent(?int $parentId) : self
    {
        $this->parent_id = $parentId;
        return $this;
    }

    /**
     * 设置等级
     */
    public function setLevel(int $level) : self
    {
        $this->level = $level;
        return $this;
    }

    /**
     * 设置状态
     */
    public function setStatus(PromoterStatusEnum $status) : self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * 启用
     */
    public function enable() : self
    {
        return $this->setStatus(PromoterStatusEnum::ENABLE);
    }


    public function apply(PromoterApply $apply) : static
    {
        $apply->promoter_id = $this->id;

        if ($apply->apply_type === PromoterApplyTypeEnum::REGISTER) {
            $this->status = PromoterStatusEnum::APPLYING;
            $this->level  = 0;
        }
        $this->applies->add($apply);


        $this->fireModelEvent('applied', false);
        return $this;
    }


    /**
     * 申请单
     * @return HasMany
     */
    public function applies() : HasMany
    {
        return $this->hasMany(PromoterApply::class, 'promoter_id', 'id');
    }

    /**
     * 禁用
     */
    public function disable() : self
    {
        return $this->setStatus(PromoterStatusEnum::DISABLE);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(PromoterGroup::class, 'group_id', 'id');
    }

    public function team() : BelongsTo
    {
        return $this->belongsTo(PromoterTeam::class, 'team_id', 'id');
    }

    public function users() : HasMany
    {
        return $this->hasMany(PromoterBindUser::class, 'promoter_id', 'id');
    }
}
