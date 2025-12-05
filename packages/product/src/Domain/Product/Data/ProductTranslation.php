<?php

namespace RedJasmine\Product\Domain\Product\Data;

use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 商品翻译数据传输对象
 *
 * 用于商品多语言翻译数据的传输和验证
 */
class ProductTranslation extends Data
{
    /**
     * 语言代码
     * 例如：zh-CN, en-US, de-DE, ja-JP
     */
    public string $locale;

    /**
     * 商品标题
     * 来自 products 表
     */
    public string $title;

    /**
     * 广告语/副标题
     * 来自 products_extension 表
     */
    public ?string $slogan = null;

    /**
     * 富文本详情（HTML格式，详细描述）
     * 来自 products_extension 表
     */
    public ?string $description = null;

    /**
     * SEO标题
     * 来自 products_extension 表
     */
    public ?string $metaTitle = null;

    /**
     * SEO关键词
     * 来自 products_extension 表
     */
    public ?string $metaKeywords = null;

    /**
     * SEO描述
     * 来自 products_extension 表
     */
    public ?string $metaDescription = null;

    /**
     * 翻译状态
     * 默认：pending（待翻译）
     */
    #[WithCast(EnumCast::class, TranslationStatusEnum::class)]
    public TranslationStatusEnum $translationStatus = TranslationStatusEnum::TRANSLATED;

}
