<?php

namespace RedJasmine\Distribution\Domain\Events\Promoter;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use RedJasmine\Distribution\Domain\Models\Promoter;

class PromoterUpgraded
{

    use Dispatchable;
    
    use SerializesModels;

    /**
     * @var Promoter
     */
    public Promoter $promoter;

    /**
     * 升级前的等级
     * @var int
     */
    public int $oldLevel;

    /**
     * 升级后的等级
     * @var int
     */
    public int $newLevel;

    /**
     * 创建一个新的事件实例
     */
    public function __construct(Promoter $promoter, int $oldLevel, int $newLevel)
    {
        $this->promoter = $promoter;
        $this->oldLevel = $oldLevel;
        $this->newLevel = $newLevel;
    }
} 