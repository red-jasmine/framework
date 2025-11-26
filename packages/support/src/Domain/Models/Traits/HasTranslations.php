<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;

/**
 * 多语言翻译 Trait
 *
 * 基于 astrotomic/laravel-translatable 封装
 * 提供统一的翻译接口和回退机制
 */
trait HasTranslations
{
    use Translatable;

    /**
     * 获取翻译字段列表
     * 子类需要覆盖此方法或定义 $translatable 属性
     */
    public function getTranslatableAttributes(): array
    {
        return $this->translatable ?? [];
    }

    /**
     * 获取默认语言
     */
    public function getDefaultLocale(): ?string
    {
        return  config('app.locale', 'zh-CN');
    }

    /**
     * 获取指定语言的翻译（带回退）
     *
     * @param string|null $locale 语言代码，null 使用当前语言
     * @param bool $withFallback 是否回退到默认语言
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function translate(?string $locale = null, bool $withFallback = true)
    {
        $locale = $locale ?: $this->getLocale();

        if (!$withFallback) {
            return parent::translate($locale, false);
        }

        // 先尝试获取指定语言的翻译
        $translation = parent::translate($locale, false);

        // 如果没有找到，回退到默认语言
        if (!$translation && $locale !== $this->getDefaultLocale()) {
            $translation = parent::translate($this->getDefaultLocale(), false);
        }

        return $translation;
    }

    /**
     * 获取翻译字段值（带回退）
     *
     * @param string $key 字段名
     * @param string|null $locale 语言代码
     * @return mixed
     */
    public function getTranslatedAttribute(string $key, ?string $locale = null)
    {
        $translation = $this->translate($locale);

        if ($translation && $translation->hasAttribute($key)) {
            return $translation->getAttribute($key);
        }

        // 回退到主表字段
        return $this->getAttribute($key);
    }

    /**
     * 设置翻译
     *
     * @param string $locale 语言代码
     * @param array $attributes 翻译字段
     * @return $this
     */
    public function setTranslation(string $locale, array $attributes)
    {
        $this->translateOrNew($locale)->fill($attributes);
        return $this;
    }

    /**
     * 查询作用域：预加载翻译
     *
     * @param Builder $query
     * @param string|null $locale 语言代码
     * @return Builder
     */
    public function scopeWithTranslation(Builder $query, ?string $locale = null): Builder
    {
        return $query->with(['translations' => function ($query) use ($locale) {
            if ($locale) {
                $query->where('locale', $locale);
            }
        }]);
    }

    /**
     * 查询作用域：按语言过滤
     *
     * @param Builder $query
     * @param string $locale 语言代码
     * @return Builder
     */
    public function scopeHasTranslation(Builder $query, string $locale): Builder
    {
        return $query->whereHas('translations', function ($q) use ($locale) {
            $q->where('locale', $locale);
        });
    }
}

