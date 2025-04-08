<?php

namespace RedJasmine\Community\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Community\Domain\Data\TopicTagData;
use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class TopicTagTransformer implements TransformerInterface
{
    /**
     * @param  Data|TopicTagData  $data
     * @param  Model|null|TopicTag  $model
     *
     * @return Model|null
     */
    public function transform(Data $data, ?Model $model = null) : ?Model
    {

        $model->name        = $data->name;
        $model->description = $data->description;
        $model->is_show     = $data->isShow;
        $model->is_public   = $data->isPublic;
        $model->sort        = $data->sort;
        $model->cluster     = $data->cluster;
        $model->icon        = $data->icon;
        $model->color       = $data->color;
        $model->status      = $data->status;

        return $model;
    }


}