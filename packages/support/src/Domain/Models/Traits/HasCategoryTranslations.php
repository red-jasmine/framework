<?php

namespace RedJasmine\Support\Domain\Models\Traits;

trait HasCategoryTranslations
{
    use HasTranslations;


    /**
     * 翻译属性缓存（由 astrotomic/laravel-translatable 自动填充）
     */
    protected array $translatedAttributes = [
        'name',
        'description',
        'cluster',
    ];

    public function getTranslationRelationKey() : string
    {
        return 'locale_id';
    }

}