<?php

namespace RedJasmine\Support\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ApprovalData extends Data
{

    #[WithCast(EnumCast::class, ApprovalStatusEnum::class)]
    public ApprovalStatusEnum $approvalStatus;

    public ?string $message;

    /**
     * 审核人
     */
    public ?UserInterface $approver = null;
}