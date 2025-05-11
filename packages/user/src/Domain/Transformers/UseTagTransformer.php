<?php

namespace RedJasmine\User\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\User\Domain\Data\UserTagData;
use RedJasmine\User\Domain\Models\UserTag;

class UseTagTransformer implements TransformerInterface
{
    /**
     * @param  Data|UserTagData  $data
     * @param  Model|UserTag  $model
     *
     * @return Model|null
     */
    public function transform(Data $data, ?Model $model = null) : ?Model
    {

        $model->category_id = $data->categoryId;
        $model->name        = $data->name;
        $model->description = $data->description;
        $model->icon        = $data->icon;
        $model->status      = $data->status;
        $model->cluster     = $data->cluster;
        $model->sort        = $data->sort;
        $model->extra       = $data->extra;
        return $model;
    }


}