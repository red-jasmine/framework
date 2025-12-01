<?php

namespace RedJasmine\Product\Domain\Attribute\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 商品属性值翻译数据传输对象
 *
 * @property string $locale 语言代码
 * @property string $name 名称
 * @property string|null $description 描述
 * @property TranslationStatusEnum $translationStatus 翻译状态
 */
class ProductAttributeValueTranslationData extends Data
{
    public string $locale;
    public string $name;
    public ?string $description = null;

    /**
     * 翻译状态
     * 默认：reviewed（已审核）
     */
    #[WithCast(EnumCast::class, TranslationStatusEnum::class)]
    public TranslationStatusEnum $translationStatus = TranslationStatusEnum::REVIEWED;
}

