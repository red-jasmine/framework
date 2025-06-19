<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class PromoterData extends Data
{

    /**
     * 所属人
     */
    public UserInterface $owner;

    /**
     * 等级
     */
    public int $level = 0;

    /**
     * 所属分组ID
     */
    public ?int $groupId = null;

    /**
     * 所属上级ID
     */
    public int $parentId = 0;

    /**
     * 状态
     */
    #[WithCast(EnumCast::class, PromoterStatusEnum::class)]
    public PromoterStatusEnum $status;

    /**
     * 所属团队ID
     */
    public ?int $teamId = null;

    /**
     * 推广员名称
     */
    public ?string $name = null;

    /**
     * 备注
     *
     * @var string|null
     */
    public ?string $remarks = null;
}
