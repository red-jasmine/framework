<?php

namespace RedJasmine\Support\Domain\Models\Traits;

trait HasCategoryTranslations
{
    use HasTranslations;

    /**
     * 可翻译字段
     *
     * 商品服务需要翻译的字段：
     * - name: 服务名称
     * - slogan: 服务口号
     * - description: 服务描述
     */
    public array $translatable = [
        'name',
        'description',
        'cluster',
    ];
    /**
     * 翻译属性缓存（由 astrotomic/laravel-translatable 自动填充）
     */
    protected array $translatedAttributes = [];

    public function getTranslationRelationKey() : string
    {
        return 'locale_id';
    }

}