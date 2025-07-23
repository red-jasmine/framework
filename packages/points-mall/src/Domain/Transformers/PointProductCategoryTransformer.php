<?php

namespace RedJasmine\PointsMall\Domain\Transformers;

use RedJasmine\PointsMall\Domain\Data\PointProductCategoryData;
use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PointProductCategoryTransformer implements TransformerInterface
{
    /**
     * 将DTO数据映射到积分商品分类模型
     */
    public function transform($data, $model): PointsProductCategory
    {
        if (!$model instanceof PointsProductCategory) {
            $model = new PointsProductCategory();
        }

        if ($data instanceof PointProductCategoryData) {
            $model->owner_type = $data->owner->getOwnerType();
            $model->owner_id = $data->owner->getOwnerId();
            $model->name = $data->name;
            $model->slug = $data->slug;
            $model->description = $data->description;
            $model->image = $data->image;
            $model->sort = $data->sort;
            $model->status = $data->status;
        }

        return $model;
    }
} 