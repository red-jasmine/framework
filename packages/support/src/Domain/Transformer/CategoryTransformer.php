<?php

namespace RedJasmine\Support\Domain\Transformer;

use RedJasmine\Support\Domain\Data\BaseCategoryData;
use RedJasmine\Support\Domain\Data\BaseCategoryTranslationData;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OwnerInterface;

class CategoryTransformer implements TransformerInterface
{
    /**
     * @param  BaseCategoryData  $data
     * @param  BaseCategoryModel  $model
     *
     * @return BaseCategoryModel
     */
    public function transform($data, $model) : BaseCategoryModel
    {
        /**
         * @var BaseCategoryModel $model
         * @var BaseCategoryData $data
         */

        $model->parent_id   = $data->parentId;
        $model->name        = $data->name;
        $model->description = $data->description;
        $model->is_leaf     = $data->isLeaf;
        $model->slug        = $data->slug;
        $model->is_show     = $data->isShow;
        $model->image       = $data->image;
        $model->icon        = $data->icon;
        $model->color       = $data->color;
        $model->status      = $data->status;
        $model->cluster     = $data->cluster;
        $model->sort        = $data->sort;
        $model->extra       = $data->extra;

        // 如果模型实现了 OwnerInterface，并且数据中有 owner 属性，则设置 owner
        if ($model instanceof OwnerInterface) {
            $model->owner = $data->owner;
        }

        $this->handleTranslations($model, $data);
        return $model;
    }


    protected function handleTranslations(BaseCategoryModel $model, BaseCategoryData $data) : void
    {

        // 判断是否定义了 关联
        if (!$model->isRelation('translations')) {
            return;
        }

        // 如果服务已存在，需要处理删除的翻译
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

        // 遍历翻译数据，设置到服务模型中
        foreach ($data->translations as $translationData) {
            // 如果翻译数据是数组格式，转换为 ProductServiceTranslation 对象
            if (is_array($translationData)) {
                $translationData = BaseCategoryTranslationData::from($translationData);
            }

            // 检查是否已存在该语言的翻译（包括已软删除的）
            $existingTranslation = $model->translations->where('locale', $translationData->locale)->first();

            // 如果存在已软删除的翻译，先恢复
            if ($existingTranslation) {
                $existingTranslation->deleted_at = null;
            }

            // 将 ProductServiceTranslation DTO 转换为数组
            $translationAttributes = $this->prepareTranslationAttributes($translationData);

            // 使用 ProductService 模型的 setTranslation 方法设置翻译
            $model->setTranslation($translationData->locale, $translationAttributes);
        }
    }


    protected function prepareTranslationAttributes(BaseCategoryTranslationData $translationData) : array
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