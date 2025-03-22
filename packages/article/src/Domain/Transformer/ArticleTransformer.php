<?php

namespace RedJasmine\Article\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Article\Domain\Data\ArticleData;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ArticleTransformer implements TransformerInterface
{
    /**
     * @param  Data|ArticleData  $data
     * @param  Model|null  $model
     *
     * @return Model|null
     */
    public function transform(Data $data, ?Model $model = null) : ?Model
    {
        /**
         * @var Article $model
         */
        $model = $model ?? Article::make();

        $model->title                 = $data->title;
        $model->image                 = $data->image;
        $model->description           = $data->description;
        $model->keywords              = $data->keywords;
        $model->category_id           = $data->categoryId;
        $model->sort                  = $data->sort;
        $model->content->content_type = $data->contentType;
        $model->content->content      = $data->content;
        return $model;

    }


}