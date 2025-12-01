<?php

namespace RedJasmine\Product\Domain\Service\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 商品服务翻译数据传输对象
 *
 * 用于商品服务多语言翻译数据的传输和验证
 */
class ProductServiceTranslation extends Data
{
    /**
     * 语言代码
     * 例如：zh-CN, en-US, de-DE, ja-JP
     */
    public string $locale;

    /**
     * 服务名称
     */
    public string $name;

    /**
     * 服务口号
     */
    public ?string $slogan = null;

    /**
     * 服务描述
     */
    public ?string $description = null;

    /**
     * 翻译状态
     * 默认：pending（待翻译）
     */
    #[WithCast(EnumCast::class, TranslationStatusEnum::class)]
    public TranslationStatusEnum $translationStatus = TranslationStatusEnum::PENDING;
}

