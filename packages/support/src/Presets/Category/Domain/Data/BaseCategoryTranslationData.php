<?php

namespace RedJasmine\Support\Presets\Category\Domain\Data;

use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class BaseCategoryTranslationData extends Data
{

    public string $locale;
    public string  $name;
    public ?string $description = null;
    public ?string $cluster     = null;


    /**
     * 翻译状态
     * 默认：pending（待翻译）
     */
    #[WithCast(EnumCast::class, TranslationStatusEnum::class)]
    public TranslationStatusEnum $translationStatus = TranslationStatusEnum::REVIEWED;
}