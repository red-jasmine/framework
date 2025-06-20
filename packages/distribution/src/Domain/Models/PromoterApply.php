<?php

namespace RedJasmine\Distribution\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyAuditStatusEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterAuditMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PromoterApply extends Model implements OperatorInterface
{
    use HasSnowflakeId;

    use HasOperator;

    public $incrementing = false;


    protected function casts() : array
    {
        return [
            'extra'        => 'array',
            'apply_method' => PromoterApplyMethodEnum::class,
            'apply_type'   => PromoterApplyTypeEnum::class,
            'audit_method' => PromoterAuditMethodEnum::class,
            'audit_status' => PromoterApplyAuditStatusEnum::class,
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


    public function approve(UserInterface $auditor, string $reason = null) : void
    {
        $this->audit_status = PromoterApplyAuditStatusEnum::APPROVED;
        $this->audit_at     = Carbon::now();
        $this->audit_reason = $reason;
        $this->auditor      = $auditor;

        // 设置审批

        $this->promoter->level  = $this->level;
        $this->promoter->status = PromoterStatusEnum::ENABLE;
    }

    /**
     * 审批人
     * @return Attribute
     */
    public function auditor() : Attribute
    {
        return Attribute::make(
            get: fn() => ($this->auditor_type && $this->auditor_id) ? UserData::from([
                'type'     => $this->auditor_type,
                'id'       => $this->auditor_id,
                'nickname' => $this->auditor_nickname ?? null,

            ]) : null,
            set: fn(?UserInterface $user = null) => [
                'auditor_type'     => $user?->getType(),
                'auditor_id'       => $user?->getID(),
                'auditor_nickname' => $user?->getNickname(),
            ]
        );
    }

}
