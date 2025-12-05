<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Domain\Events\PromoterApply\PromoterApplyApproved;
use RedJasmine\Distribution\Domain\Events\PromoterApply\PromoterApplyRejected;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApprovalMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Domain\Data\UserData;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasApproval;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PromoterApply extends Model implements OperatorInterface
{
    use HasSnowflakeId;
    use HasOperator;
    use HasApproval;

    public $incrementing = false;


    protected $dispatchesEvents = [
        'approvalPass'   => PromoterApplyApproved::class,
        'approvalReject' => PromoterApplyRejected::class,
        'approvalRevoke' => PromoterApplyRejected::class,
    ];

    protected function casts() : array
    {
        return [
            'extra'           => 'array',
            'apply_method'    => PromoterApplyMethodEnum::class,
            'apply_type'      => PromoterApplyTypeEnum::class,
            'approval_method' => PromoterApprovalMethodEnum::class,
            'approval_status' => ApprovalStatusEnum::class,
            'apply_at'        => 'datetime',
            'approval_at'     => 'datetime',
        ];
    }


    public function promoterLevel() : BelongsTo
    {
        return $this->belongsTo(PromoterLevel::class, 'level', 'level');
    }

    public function promoter() : BelongsTo
    {
        return $this->belongsTo(Promoter::class, 'promoter_id', 'id');
    }

    public function approvalPass(ApprovalData $data) : void
    {
        $this->approval_at     = Carbon::now();
        $this->approval_reason = $data->message;
        $this->approver        = $data->approver;

        // 更新关联的 Promoter 状态和等级
        if ($this->promoter) {
            $this->promoter->level  = $this->level;
            $this->promoter->status = PromoterStatusEnum::ENABLE;
        }


    }


    /**
     * 审批拒绝时的业务逻辑
     *
     * @param  ApprovalData  $data
     *
     * @return void
     */
    public function approvalReject(ApprovalData $data) : void
    {

        $this->approval_at     = Carbon::now();
        $this->approval_reason = $data->message;
        $this->approver        = $data->approver;

        // 审核拒绝时，分销员状态保持不变或设置为禁用
        if ($this->promoter && $this->apply_type === PromoterApplyTypeEnum::REGISTER) {
            $this->promoter->disable();
        }
    }

    /**
     * 审批撤销时的业务逻辑
     *
     * @param  ApprovalData  $data
     *
     * @return void
     */
    public function approvalRevoke(ApprovalData $data) : void
    {

        $this->approval_at     = Carbon::now();
        $this->approval_reason = $data->message;
        $this->approver        = $data->approver;


        // 撤销审批时的业务逻辑
        if ($this->promoter && $this->apply_type === PromoterApplyTypeEnum::REGISTER) {
            $this->promoter->disable();
        }
    }

    /**
     * 审批人
     * @return Attribute
     */
    public function approver() : Attribute
    {
        return Attribute::make(
            get: fn() => ($this->approver_type && $this->approver_id) ? UserData::from([
                'type'     => $this->approver_type,
                'id'       => $this->approver_id,
                'nickname' => $this->approver_nickname ?? null,

            ]) : null,
            set: fn(?UserInterface $user = null) => [
                'approver_type'     => $user?->getType(),
                'approver_id'       => $user?->getID(),
                'approver_nickname' => $user?->getNickname(),
            ]
        );
    }


    public function scopePending(Builder $builder)
    {
        return $builder->where('approval_status', ApprovalStatusEnum::PENDING);
    }


    public function scopeOnlyPromoter(Builder $builder, Promoter $promoter)
    {
        return $builder->where('promoter_id', $promoter->id);
    }

}
