<?php

namespace RedJasmine\Distribution\Domain\Events\Promoter;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use RedJasmine\Distribution\Domain\Models\Promoter;

class PromoterEnabled
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var Promoter
     */
    public Promoter $promoter;

    /**
     * 启用原因
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