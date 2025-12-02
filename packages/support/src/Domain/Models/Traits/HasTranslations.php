<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\Traits\Translations\Relationship;
use RedJasmine\Support\Domain\Models\Traits\Translations\Scopes;

/**
 * 多语言翻译 Trait
 *
 * 基于 astrotomic/laravel-translatable 封装
 * 提供统一的翻译接口和回退机制
 */
trait HasTranslations
{


    use Relationship, Scopes;

    public static function bootHasTranslations(): void
    {
        static::saved(function (Model $model) {
            /* @var static $model */
            return $model->saveTranslations();
        });

        static::deleting(function (Model $model) {
            /* @var static $model */
            if (self::$deleteTranslationsCascade === true) {
                return $model->deleteTranslations();
            }
        });
    }
    protected static $autoloadTranslations = null;

    protected static $deleteTranslationsCascade = false;

    protected $defaultLocale;

    public static function defaultAutoloadTranslations() : void
    {
        self::$autoloadTranslations = null;
    }

    public static function disableAutoloadTranslations() : void
    {
        self::$autoloadTranslations = false;
    }

    public static function enableAutoloadTranslations() : void
    {
        self::$autoloadTranslations = true;
    }

    public static function disableDeleteTranslationsCascade() : void
    {
        self::$deleteTranslationsCascade = false;
    }

    public static function enableDeleteTranslationsCascade() : void
    {
        self::$deleteTranslationsCascade = true;
    }

    public function getTranslationsArray() : array
    {
        $translations = [];

        foreach ($this->translations as $translation) {
            foreach ($this->translatedAttributes as $attr) {
                $translations[$translation->{$this->getLocaleKey()}][$attr] = $translation->{$attr};
            }
        }

        return $translations;
    }

    /**
     * @internal will change to protected
     */
    public function getLocaleKey() : string
    {
        return $this->localeKey ?: config('translatable.locale_key', 'locale');
    }

    public function hasTranslation(?string $locale = null) : bool
    {
        $locale = $locale ?: $this->locale();

        foreach ($this->translations as $translation) {
            if ($translation->getAttribute($this->getLocaleKey()) == $locale) {
                return true;
            }
        }

        return false;
    }

    protected function locale() : string
    {
        if ($this->getDefaultLocale()) {
            return $this->getDefaultLocale();
        }

        return config('app.locale');
    }

    public function getDefaultLocale() : ?string
    {
        return $this->defaultLocale;
    }

    public function setDefaultLocale(?string $locale)
    {
        $this->defaultLocale = $locale;

        return $this;
    }
    // TODO 是否需要自动转换 自动转换 传入表单 会输出翻译后的数据，和原始表单不一致
    public function getAttribute23($key)
    {
        [$attribute, $locale] = $this->getAttributeAndLocale($key);

        if ($this->isTranslationAttribute($attribute)) {
            if ($this->getTranslation($locale) === null) {
                return $this->getAttributeValue($attribute);
            }

            // If the given $attribute has a mutator, we push it to $attributes and then call getAttributeValue
            // on it. This way, we can use Eloquent's checking for Mutation, type casting, and
            // Date fields.
            if ($this->hasGetMutator($attribute)) {
                $this->attributes[$attribute] = $this->getAttributeOrFallback($locale, $attribute);

                return $this->getAttributeValue($attribute);
            }

            return $this->getAttributeOrFallback($locale, $attribute);
        }

        return parent::getAttribute($key);
    }
    public function attributesToArray23()
    {
        $attributes = parent::attributesToArray();

        if (
            (! $this->relationLoaded('translations') && ! $this->toArrayAlwaysLoadsTranslations() && is_null(self::$autoloadTranslations))
            || self::$autoloadTranslations === false
        ) {
            return $attributes;
        }

        $hiddenAttributes = $this->getHidden();

        foreach ($this->translatedAttributes as $field) {
            if (in_array($field, $hiddenAttributes)) {
                continue;
            }

            $attributes[$field] = $this->getAttributeOrFallback(null, $field);
        }

        return $attributes;
    }


    public function isWrapperAttribute(string $key) : bool
    {
        return $key === config('translatable.translations_wrapper');
    }

    public function replicateWithTranslations(?array $except = null) : Model
    {
        $newInstance = $this->replicate($except);

        unset($newInstance->translations);
        foreach ($this->translations as $translation) {
            $newTranslation = $translation->replicate();
            $newInstance->translations->add($newTranslation);
        }

        return $newInstance;
    }


    public function translate(?string $locale = null, bool $withFallback = false) : ?Model
    {
        return $this->getTranslation($locale, $withFallback);
    }

    public function getTranslation(?string $locale = null, ?bool $withFallback = null) : ?Model
    {
        $configFallbackLocale = $this->getFallbackLocale();
        $locale               = $locale ?: $this->locale();
        $withFallback         = $withFallback === null ? $this->useFallback() : $withFallback;
        $fallbackLocale       = $this->getFallbackLocale($locale);

        if ($translation = $this->getTranslationByLocaleKey($locale)) {
            return $translation;
        }

        if ($withFallback && $fallbackLocale) {
            if ($translation = $this->getTranslationByLocaleKey($fallbackLocale)) {
                return $translation;
            }

            if (
                is_string($configFallbackLocale)
                && $fallbackLocale !== $configFallbackLocale
                && $translation = $this->getTranslationByLocaleKey($configFallbackLocale)
            ) {
                return $translation;
            }
        }


        return null;
    }

    protected function getFallbackLocale(?string $locale = null) : ?string
    {


        return config('app.fallback_locale');
    }

    protected function useFallback() : bool
    {
        if (isset($this->useTranslationFallback) && is_bool($this->useTranslationFallback)) {
            return $this->useTranslationFallback;
        }

        return (bool) true;
    }

    protected function getTranslationByLocaleKey(string $key) : ?Model
    {
        if (
            $this->relationLoaded('translation')
            && $this->translation
            && $this->translation->getAttribute($this->getLocaleKey()) == $key
        ) {
            return $this->translation;
        }

        return $this->translations->firstWhere($this->getLocaleKey(), $key);
    }

    public function translateOrDefault(?string $locale = null) : ?Model
    {
        return $this->getTranslation($locale, true);
    }

    public function translateOrNew(?string $locale = null) : Model
    {
        return $this->getTranslationOrNew($locale);
    }

    public function getTranslationOrNew(?string $locale = null) : Model
    {
        $locale = $locale ?: $this->locale();

        if (($translation = $this->getTranslation($locale, false)) === null) {
            $translation = $this->getNewTranslation($locale);
        }

        return $translation;
    }

    public function getNewTranslation(string $locale) : Model
    {
        $modelName = $this->getTranslationModelName();

        /** @var Model $translation */
        $translation = new $modelName;
        $translation->setAttribute($this->getLocaleKey(), $locale);
        $translation->setAttribute($this->getTranslationRelationKey(), $this->getKey());
        $this->translations->add($translation);

        return $translation;
    }

    public function setAttribute23($key, $value)
    {
        [$attribute, $locale] = $this->getAttributeAndLocale($key);

        if ($this->isTranslationAttribute($attribute)) {
            $this->getTranslationOrNew($locale)->$attribute = $value;

            return $this;
        }

        return parent::setAttribute($key, $value);
    }

    protected function getAttributeAndLocale(string $key) : array
    {
        if (Str::contains($key, ':')) {
            return explode(':', $key);
        }

        return [$key, $this->locale()];
    }

    public function isTranslationAttribute(string $key) : bool
    {
        return in_array($key, $this->translatedAttributes);
    }

    public function translateOrFail(string $locale) : Model
    {
        return $this->getTranslationOrFail($locale);
    }

    public function getTranslationOrFail(string $locale) : Model
    {
        if (($translation = $this->getTranslation($locale, false)) === null) {
            throw (new ModelNotFoundException)->setModel($this->getTranslationModelName(), $locale);
        }

        return $translation;
    }

    public function __isset($key)
    {
        return $this->isTranslationAttribute($key) || parent::__isset($key);
    }

    protected function saveTranslations() : bool
    {
        $saved = true;

        if (!$this->relationLoaded('translations')) {
            return $saved;
        }

        foreach ($this->translations as $translation) {
            if ($saved && $this->isTranslationDirty($translation)) {
                if (!empty($connectionName = $this->getConnectionName())) {
                    $translation->setConnection($connectionName);
                }

                $translation->setAttribute($this->getTranslationRelationKey(), $this->getKey());
                $saved = $translation->save();
            }
        }

        return $saved;
    }

    protected function isTranslationDirty(Model $translation) : bool
    {
        $dirtyAttributes = $translation->getDirty();
        unset($dirtyAttributes[$this->getLocaleKey()]);
        unset($dirtyAttributes[$this->getTranslationRelationKey()]);

        return count($dirtyAttributes) > 0;
    }

    protected function getAttributeOrFallback(?string $locale, string $attribute)
    {
        $translation = $this->getTranslation($locale);

        if (
            (
                !$translation instanceof Model
                || $this->isEmptyTranslatableAttribute($attribute, $translation->$attribute)
            )
            && $this->usePropertyFallback()
        ) {
            $translation = $this->getTranslation($this->getFallbackLocale(), false);
        }

        if ($translation instanceof Model) {
            return $translation->$attribute;
        }

        return null;
    }

    protected function isEmptyTranslatableAttribute(string $key, $value) : bool
    {
        return empty($value);
    }

    protected function usePropertyFallback() : bool
    {
        return $this->useFallback() && config('translatable.use_property_fallback', false);
    }

    protected function toArrayAlwaysLoadsTranslations() : bool
    {
        return config('translatable.to_array_always_loads_translations', true);
    }


}

