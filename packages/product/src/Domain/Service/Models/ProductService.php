<?php

namespace RedJasmine\Product\Domain\Service\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasTranslations;

class ProductService extends BaseCategoryModel implements OperatorInterface
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
        'name',        // 服务名称
        'slogan',      // 服务口号
        'description', // 服务描述
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
        return $this->hasMany(ProductServiceTranslation::class, 'product_service_id');
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

}
