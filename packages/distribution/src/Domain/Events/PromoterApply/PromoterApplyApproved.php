<?php

namespace RedJasmine\Distribution\Domain\Events\PromoterApply;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Distribution\Domain\Models\PromoterApply;

/**
 * 分销员申请审核通过事件
 */
class PromoterApplyApproved
{
    use Dispatchable;
    use SerializesModels;

    /**
     * 分销员申请记录
     */
    public PromoterApply $apply;

    /**
     * 创建事件实例
     *
     * @param PromoterApply $apply
     */
    public function __construct(PromoterApply $apply)
    {
        $this->apply = $apply;
    }
}