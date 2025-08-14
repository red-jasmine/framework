<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Data;

use RedJasmine\Message\Domain\Models\Enums\BizEnum;
use RedJasmine\Message\Domain\Models\Enums\StatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息分类数据传输对象
 */
class MessageCategoryData extends Data
{
    public function __construct(

        public UserInterface $owner,
        #[WithCast(EnumCast::class, BizEnum::class)]
        public BizEnum $biz,
        public string $name,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $color = null,
        public int $sort = 0,
        
        #[WithCast(EnumCast::class, StatusEnum::class)]
        public StatusEnum $status = StatusEnum::ENABLE,
    ) {
    }

    /**
     * 是否启用
     */
    public function isEnabled(): bool
    {
        return $this->status === StatusEnum::ENABLE;
    }

    /**
     * 是否禁用
     */
    public function isDisabled(): bool
    {
        return $this->status === StatusEnum::DISABLE;
    }

    /**
     * 启用分类
     */
    public function enable(): self
    {
        $this->status = StatusEnum::ENABLE;
        return $this;
    }

    /**
     * 禁用分类
     */
    public function disable(): self
    {
        $this->status = StatusEnum::DISABLE;
        return $this;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * 设置图标和颜色
     */
    public function setAppearance(?string $icon = null, ?string $color = null): self
    {
        if ($icon !== null) {
            $this->icon = $icon;
        }

        if ($color !== null) {
            $this->color = $color;
        }

        return $this;
    }
}
