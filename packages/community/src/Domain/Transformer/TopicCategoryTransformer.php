<?php

namespace RedJasmine\Community\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Community\Domain\Data\TopicCategoryData;
use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class TopicCategoryTransformer implements TransformerInterface
{
    /**
     * @param  Data|TopicCategoryData  $data
     * @param  Model|null|TopicCategory  $model
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

        return $model;
    }


}