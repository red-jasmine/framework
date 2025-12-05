<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Foundation\Data\Data;

class PromoterLevelData extends Data
{

    /**
     * 等级 默认 第一级别 分销员
     */
    public int $level = 1;

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

    /**
     * @var ConditionData[]
     */
    public array $upgrades = [];

    /**
     * 保级条件
     */
    /**
     * @var ConditionData[]
     */
    public array $keeps = [];

    /**
     * 商品佣金比例
     * @var int
     */
    public int $productRatio = 0;

    /**
     * 上级佣金比例
     * @var int
     */
    public int $parentRatio = 0;
    /**
     * 权益
     */
    public ?array $benefits = null;
} 