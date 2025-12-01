<?php

namespace RedJasmine\Product\Domain\Service\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Service\Data\ProductServiceData;
use RedJasmine\Product\Domain\Service\Data\ProductServiceTranslation;
use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 商品服务转换器
 *
 * 负责将 ProductServiceData 转换为 ProductService 模型
 * 支持多语言翻译数据
 */
class ProductServiceTransformer extends CategoryTransformer implements TransformerInterface
{
    /**
     * 转换数据到模型
     *
     * @param Data|ProductServiceData $data
     * @param Model|ProductService $model
     * @return ProductService
     */
    public function transform($data, $model): ProductService
    {
        /** @var ProductService $model */
        /** @var ProductServiceData $data */

        // 调用父类方法处理基础字段
        $model = parent::transform($data, $model);

        // 处理翻译数据
        $this->handleTranslations($model, $data);

        return $model;
    }

    /**
     * 处理翻译数据
     *
     * @param ProductService $service
     * @param ProductServiceData $data
     * @return void
     */
    protected function handleTranslations(ProductService $service, ProductServiceData $data): void
    {
        // 如果服务已存在，需要处理删除的翻译
        if ($service->exists) {
            // 获取数据库中现有的未删除翻译（包括已软删除的）
            $existingTranslations = $service->translations()
                                          ->withTrashed()
                                          ->get();

            $service->setRelation('translations', $existingTranslations);

            // 软删除表单中不存在的翻译
            foreach ($service->translations as $translation) {
                $translation->deleted_at = now(); // 使用软删除
            }
        }

        // 如果没有翻译数据，直接返回
        if (empty($data->translations)) {
            return;
        }

        // 遍历翻译数据，设置到服务模型中
        foreach ($data->translations as $translationData) {
            // 如果翻译数据是数组格式，转换为 ProductServiceTranslation 对象
            if (is_array($translationData)) {
                $translationData = ProductServiceTranslation::from($translationData);
            }

            // 如果不是 ProductServiceTranslation 实例，跳过
            if (!($translationData instanceof ProductServiceTranslation)) {
                continue;
            }

            // 检查是否已存在该语言的翻译（包括已软删除的）
            $existingTranslation = $service->translations->where('locale', $translationData->locale)
                                                       ->first();

            // 如果存在已软删除的翻译，先恢复
            if ($existingTranslation) {
                $existingTranslation->deleted_at = null;
            }

            // 将 ProductServiceTranslation DTO 转换为数组
            $translationAttributes = $this->prepareTranslationAttributes($translationData);

            // 使用 ProductService 模型的 setTranslation 方法设置翻译
            $service->setTranslation($translationData->locale, $translationAttributes);
        }
    }

    /**
     * 准备翻译属性数组
     *
     * @param ProductServiceTranslation $translationData
     * @return array
     */
    protected function prepareTranslationAttributes(ProductServiceTranslation $translationData): array
    {
        $attributes = [
            'name' => $translationData->name,
            'slogan' => $translationData->slogan,
            'description' => $translationData->description,
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

