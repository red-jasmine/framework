<?php

namespace RedJasmine\Product\Domain\Attribute\Transformer;

use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeValueData;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeValueTranslationData;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * 商品属性值转换器
 *
 * 负责将 ProductAttributeValueData 转换为 ProductAttributeValue 模型
 * 支持多语言翻译数据
 */
class ProductAttributeValueTransformer implements TransformerInterface
{
    /**
     * @param ProductAttributeValueData $data
     * @param ProductAttributeValue $model
     * @return ProductAttributeValue
     */
    public function transform($data, $model): Model
    {
        /** @var ProductAttributeValue $model */
        /** @var ProductAttributeValueData $data */

        $model->aid = $data->aid;
        $model->name = $data->name;
        $model->description = $data->description;
        $model->status = $data->status;
        $model->group_id = $data->groupId;
        $model->sort = $data->sort;
        //$model->extra = $data->extra;

        $this->handleTranslations($model, $data);
        return $model;
    }

    /**
     * 处理翻译数据
     */
    protected function handleTranslations(ProductAttributeValue $model, ProductAttributeValueData $data): void
    {
        // 判断是否定义了关联
        if (!$model->isRelation('translations')) {
            return;
        }

        // 如果模型已存在，需要处理删除的翻译
        if ($model->exists) {
            // 获取数据库中现有的未删除翻译（包括已软删除的）
            $existingTranslations = $model->translations()
                ->withTrashed()
                ->get();

            $model->setRelation('translations', $existingTranslations);

            // 软删除表单中不存在的翻译
            foreach ($model->translations as $translation) {
                $translation->deleted_at = now(); // 使用软删除
            }
        }

        // 如果没有翻译数据，直接返回
        if (empty($data->translations)) {
            return;
        }

        // 遍历翻译数据，设置到模型中
        foreach ($data->translations as $translationData) {
            // 如果翻译数据是数组格式，转换为 ProductAttributeValueTranslationData 对象
            if (is_array($translationData)) {
                $translationData = ProductAttributeValueTranslationData::from($translationData);
            }

            // 检查是否已存在该语言的翻译（包括已软删除的）
            $existingTranslation = $model->translations->where('locale', $translationData->locale)->first();

            // 如果存在已软删除的翻译，先恢复
            if ($existingTranslation) {
                $existingTranslation->deleted_at = null;
            }

            // 将翻译 DTO 转换为数组
            $translationAttributes = $this->prepareTranslationAttributes($translationData);

            // 使用模型的 setTranslation 方法设置翻译
            $model->setTranslation($translationData->locale, $translationAttributes);
        }
    }

    /**
     * 准备翻译属性
     */
    protected function prepareTranslationAttributes(ProductAttributeValueTranslationData $translationData): array
    {
        $attributes = $translationData->toArray();

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

