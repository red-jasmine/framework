<?php

namespace RedJasmine\Product\Domain\Product\Data;

use RedJasmine\Product\Domain\Media\Models\Enums\MediaTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ProductMedia extends Data
{
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
     * MIME ID
     * @var string|null
     */
    public ?string $mimeId = null;

    /**
     * 文件路径（相对路径，自动拼接CDN地址）
     */
    public string $path;

    /**
     * 文件名
     */
    public ?string $fileName = null;


    /**
     * 排序位置
     */
    public int $position = 0;

    /**
     * 是否主图
     */
    public bool $isPrimary = false;


}