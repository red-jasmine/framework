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
     * @param  Model|Article  $model
     *
     * @return Model|null
     */
    public function transform(Data $data, ?Model $model = null) : ?Model
    {
        /**
         * @var Article $model
         */
        $model = $model ?? Article::make();

        $model->title                   = $data->title;
        $model->image                   = $data->image;
        $model->description             = $data->description;
        $model->keywords                = $data->keywords;
        $model->category_id             = $data->categoryId ?? $model->category_id;
        $model->sort                    = $data->sort;
        $model->is_show                 = $data->isShow;
        $model->extension->content_type = $data->contentType;
        $model->extension->content      = $data->content;
        $model->setRelation('tags', collect($data->tags));

        return $model;

    }


}