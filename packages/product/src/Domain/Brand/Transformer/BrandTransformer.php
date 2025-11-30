<?php

namespace RedJasmine\Product\Domain\Brand\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Brand\Data\BrandData;
use RedJasmine\Product\Domain\Brand\Data\BrandTranslation;
use RedJasmine\Product\Domain\Brand\Models\ProductBrand;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 品牌转换器
 *
 * 负责将 BrandData 转换为 ProductBrand 模型
 * 支持多语言翻译数据
 */
class BrandTransformer extends CategoryTransformer implements TransformerInterface
{
    /**
     * 转换数据到模型
     *
     * @param Data|BrandData $data
     * @param Model|ProductBrand $model
     * @return ProductBrand
     */
    public function transform($data, $model): ProductBrand
    {
        /** @var ProductBrand $model */
        /** @var BrandData $data */

        // 调用父类方法处理基础字段
        $model = parent::transform($data, $model);

        // 处理翻译数据
        $this->handleTranslations($model, $data);

        return $model;
    }

    /**
     * 处理翻译数据
     *
     * @param ProductBrand $brand
     * @param BrandData $data
     * @return void
     */
    protected function handleTranslations(ProductBrand $brand, BrandData $data): void
    {
        // 如果品牌已存在，需要处理删除的翻译
        if ($brand->exists) {
            // 获取数据库中现有的未删除翻译（包括已软删除的）
            $existingTranslations = $brand->translations()
                                          ->withTrashed()
                                          ->get();

            $brand->setRelation('translations', $existingTranslations);

            // 软删除表单中不存在的翻译
            foreach ($brand->translations as $translation) {
                $translation->deleted_at = now(); // 使用软删除
            }
        }

        // 如果没有翻译数据，直接返回
        if (empty($data->translations)) {
            return;
        }

        // 遍历翻译数据，设置到品牌模型中
        foreach ($data->translations as $translationData) {
            // 如果翻译数据是数组格式，转换为 BrandTranslation 对象
            if (is_array($translationData)) {
                $translationData = BrandTranslation::from($translationData);
            }

            // 如果不是 BrandTranslation 实例，跳过
            if (!($translationData instanceof BrandTranslation)) {
                continue;
            }

            // 检查是否已存在该语言的翻译（包括已软删除的）
            $existingTranslation = $brand->translations->where('locale', $translationData->locale)
                                                       ->first();

            // 如果存在已软删除的翻译，先恢复
            if ($existingTranslation) {
                $existingTranslation->deleted_at = null;
            }

            // 将 BrandTranslation DTO 转换为数组
            $translationAttributes = $this->prepareTranslationAttributes($translationData);

            // 使用 ProductBrand 模型的 setTranslation 方法设置翻译
            $brand->setTranslation($translationData->locale, $translationAttributes);
        }
    }

    /**
     * 准备翻译属性数组
     *
     * @param BrandTranslation $translationData
     * @return array
     */
    protected function prepareTranslationAttributes(BrandTranslation $translationData): array
    {
        $attributes = [
            'name' => $translationData->name,
            'description' => $translationData->description,
            'slogan' => $translationData->slogan,
            'translation_status' => $translationData->translationStatus->value,
        ];

        // 如果翻译状态为已翻译，设置翻译时间
        if ($translationData->translationStatus->value === 'translated' ||
            $translationData->translationStatus->value === 'reviewed') {
            $attributes['translated_at'] = now();
        }

        // 如果翻译状态为已审核，设置审核时间
        if ($translationData->translationStatus->value === 'reviewed') {
            $attributes['reviewed_at'] = now();
        }

        return $attributes;
    }
}

