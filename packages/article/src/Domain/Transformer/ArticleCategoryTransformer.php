<?php

namespace RedJasmine\Article\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Article\Domain\Data\ArticleCategoryData;
use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ArticleCategoryTransformer implements TransformerInterface
{
    /**
     * @param  Data|ArticleCategoryData  $data
     * @param  Model|null|ArticleCategory  $model
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