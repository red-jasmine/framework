<?php

namespace RedJasmine\Article\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Article\Domain\Data\ArticleTagData;
use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ArticleTagTransformer implements TransformerInterface
{
    /**
     * @param  Data|ArticleTagData  $data
     * @param  Model|null|ArticleTag  $model
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