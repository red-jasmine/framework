<?php

namespace RedJasmine\Product\Domain\Brand\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasTranslations;


/**
 * 品牌模型
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $slogan
 */
class ProductBrand extends BaseCategoryModel
{
    use HasTranslations;

    /**
     * 可翻译字段
     *
     * 品牌需要翻译的字段：
     * - name: 品牌名称
     * - description: 品牌描述
     * - slogan: 品牌口号
     */
    public array $translatable = [
        'name',        // 品牌名称
        'description', // 品牌描述
        'slogan',      // 品牌口号
    ];
    /**
     * 翻译属性缓存（由 astrotomic/laravel-translatable 自动填充）
     */
    protected array $translatedAttributes = [];
    /**
     * 翻译关联
     */
    public function translations() : HasMany
    {
        return $this->hasMany(ProductBrandTranslation::class, 'product_brand_id');
    }

    /**
     * 获取翻译后的名称
     *
     * @param  string|null  $locale
     *
     * @return string
     */
    public function getTranslatedName(?string $locale = null) : string
    {
        return $this->getTranslatedAttribute('name', $locale) ?: $this->name;
    }

    /**
     * 获取翻译后的描述
     *
     * @param  string|null  $locale
     *
     * @return string|null
     */
    public function getTranslatedDescription(?string $locale = null) : ?string
    {
        return $this->getTranslatedAttribute('description', $locale) ?: $this->description;
    }

    /**
     * 获取翻译后的口号
     *
     * 注意：slogan 字段只在翻译表中存在，主表没有此字段
     *
     * @param  string|null  $locale
     *
     * @return string|null
     */
    public function getTranslatedSlogan(?string $locale = null) : ?string
    {
        $translation = $this->translate($locale);
        return $translation?->slogan;
    }

    public function isAllowUse() : bool
    {
        return $this->status === UniversalStatusEnum::ENABLE;
    }

}
