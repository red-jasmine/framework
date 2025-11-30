<?php

namespace RedJasmine\Product\Domain\Media\Data;

use RedJasmine\Product\Domain\Media\Models\Enums\MediaTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 媒体资源数据传输对象
 *
 * 用于商品媒体资源的传输和验证
 */
class Media extends Data
{
    /**
     * 所有者
     */
    public UserInterface $owner;

    /**
     * 商品ID（可选，支持媒体独立管理）
     */
    public ?int $productId = null;

    /**
     * 变体ID（可选，支持媒体独立管理）
     *
     */
    public ?int $variantId = null;

    /**
     * 媒体类型
     */
    #[WithCast(EnumCast::class, MediaTypeEnum::class)]
    public MediaTypeEnum $mediaType;

    /**
     * MIME类型
     */
    public ?string $mimeType = null;

    /**
     * 文件路径（相对路径，自动拼接CDN地址）
     */
    public string $path;

    /**
     * 文件名
     */
    public ?string $fileName = null;


    /**
     * 替代文本（用于无障碍访问和SEO）
     */
    public ?string $altText = null;

    /**
     * 排序位置
     */
    public int $position = 0;

    /**
     * 是否主图
     */
    public bool $isPrimary = false;

    /**
     * 是否启用
     */
    public bool $isEnabled = true;

    /**
     * 扩展字段
     */
    public ?array $extra = null;
}
