<?php

namespace RedJasmine\Product\Domain\Brand\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 品牌翻译数据传输对象
 *
 * 用于品牌多语言翻译数据的传输和验证
 */
class BrandTranslation extends Data
{
    /**
     * 语言代码
     * 例如：zh-CN, en-US, de-DE, ja-JP
     */
    public string $locale;

    /**
     * 品牌名称
     */
    public string $name;

    /**
     * 品牌描述
     */
    public ?string $description = null;

    /**
     * 品牌口号
     */
    public ?string $slogan = null;

    /**
     * 翻译状态
     * 默认：pending（待翻译）
     */
    #[WithCast(EnumCast::class, TranslationStatusEnum::class)]
    public TranslationStatusEnum $translationStatus = TranslationStatusEnum::PENDING;
}

