<?php

namespace RedJasmine\Distribution\Domain\Events\Promoter;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Distribution\Domain\Models\Promoter;

class PromoterAudited
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Promoter
     */
    public Promoter $promoter;

    /**
     * 审核结果
     * @var bool
     */
    public bool $approved;

    /**
     * 审核备注
     * @var string
     */
    public string $remark;

    /**
     * 创建一个新的事件实例
     */
    public function __construct(Promoter $promoter, bool $approved, string $remark)
    {
        $this->promoter = $promoter;
        $this->approved = $approved;
        $this->remark = $remark;
    }
} 