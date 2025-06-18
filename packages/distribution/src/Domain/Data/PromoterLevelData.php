<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Data\Data;

class PromoterLevelData extends Data
{
    
    
    /**
     * 等级
     */
    public int $level = 0;
    
    /**
     * 等级名称
     */
    public string $name;
    
    /**
     * 描述
     */
    public ?string $description = null;
    
    /**
     * 图标
     */
    public ?string $icon = null;
    
    /**
     * 图片
     */
    public ?string $image = null;
    
    /**
     * 升级条件
     */
    public ?array $upgrades = null;
    
    /**
     * 保级条件
     */
    public ?array $keeps = null;
    
    /**
     * 佣金比例
     */
    public ?array $ratios = null;
} 