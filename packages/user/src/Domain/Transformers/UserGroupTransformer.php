<?php

namespace RedJasmine\User\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\BaseCategoryData;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Models\UserTagCategory;

class UserGroupTransformer implements TransformerInterface
{
    /**
     * @param  Data|BaseCategoryData  $data
     * @param  Model|UserGroup  $model
     *
     * @return Model|null
     */
    public function transform(Data $data, ?Model $model = null) : ?Model
    {


        $model->parent_id   = $data->parentId;
        $model->name        = $data->name;
        $model->description = $data->description;
        $model->image       = $data->image;
        $model->is_leaf     = $data->isLeaf;
        $model->is_show     = $data->isShow;
        $model->status      = $data->status;
        $model->cluster     = $data->cluster;
        $model->sort        = $data->sort;
        $model->extra       = $data->extra;
        return $model;
    }


}