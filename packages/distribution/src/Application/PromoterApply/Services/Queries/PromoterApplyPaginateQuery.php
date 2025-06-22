<?php

namespace RedJasmine\Distribution\Application\PromoterApply\Services\Queries;

use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApprovalMethodEnum;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 分销员申请分页查询
 */
class PromoterApplyPaginateQuery extends PaginateQuery
{
    /**
     * 分销员ID
     */
    public ?int $promoterId = null;

    /**
     * 申请等级
     */
    public ?int $level = null;

    /**
     * 申请类型
     */
    #[WithCast(EnumCast::class, PromoterApplyTypeEnum::class)]
    public ?PromoterApplyTypeEnum $applyType = null;

    /**
     * 申请方式
     */
    #[WithCast(EnumCast::class, PromoterApplyMethodEnum::class)]
    public ?PromoterApplyMethodEnum $applyMethod = null;

    /**
     * 审核方式
     */
    #[WithCast(EnumCast::class, PromoterApprovalMethodEnum::class)]
    public ?PromoterApprovalMethodEnum $approvalMethod = null;


    /**
     * 新的审批状态
     */
    #[WithCast(EnumCast::class, ApprovalStatusEnum::class)]
    public ?ApprovalStatusEnum $approvalStatus = null;

    /**
     * 申请开始时间
     */
    public ?string $applyStartTime = null;

    /**
     * 申请结束时间
     */
    public ?string $applyEndTime = null;

    /**
     * 审核开始时间
     */
    public ?string $approvalStartTime = null;

    /**
     * 审核结束时间
     */
    public ?string $approvalEndTime = null;

    /**
     * 审核人类型
     */
    public ?string $approverType = null;

    /**
     * 审核人ID
     */
    public ?int $approverId = null;
}