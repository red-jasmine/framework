<?php

namespace RedJasmine\Support\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ApprovalData extends Data
{
    public ?string $message;

    #[WithCast(EnumCast::class, ApprovalStatusEnum::class)]
    public ApprovalStatusEnum $approvalStatus;
}