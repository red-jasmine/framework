<?php

namespace RedJasmine\Support\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\TranslationStatusEnum;
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