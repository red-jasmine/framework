<?php

namespace RedJasmine\Distribution\Domain\Events\Promoter;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Distribution\Domain\Models\Promoter;

class PromoterDeleted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Promoter
     */
    public Promoter $promoter;

    /**
     * 删除原因
     * @var string
     */
    public string $reason;

    /**
     * 创建一个新的事件实例
     */
    public function __construct(Promoter $promoter, string $reason)
    {
        $this->promoter = $promoter;
        $this->reason = $reason;
    }
} 