<?php

namespace RedJasmine\Support\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;

class CategoryTransformer implements TransformerInterface
{
    /**
     * @param  BaseCategoryData  $data
     * @param  Model|null|BaseCategoryModel  $model
     *
     * @return Model|null
     */
    public function transform(Data $data, ?Model $model = null) : ?Model
    {
        /**
         * @var BaseCategoryModel $model
         * @var BaseCategoryData $data
         */

        $model->parent_id   = $data->parentId;
        $model->name        = $data->name;
        $model->description = $data->description;
        $model->is_leaf     = $data->isLeaf;
        $model->slug     = $data->slug;
        $model->is_show     = $data->isShow;
        $model->image       = $data->image;
        $model->icon        = $data->icon;
        $model->color       = $data->color;
        $model->status      = $data->status;
        $model->cluster     = $data->cluster;
        $model->sort        = $data->sort;
        $model->extra       = $data->extra;

        return $model;
    }


}