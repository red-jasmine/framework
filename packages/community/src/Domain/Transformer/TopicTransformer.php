<?php

namespace RedJasmine\Community\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Community\Domain\Data\TopicData;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class TopicTransformer implements TransformerInterface
{
    /**
     * @param  Data|TopicData  $data
     * @param  Model|null|Topic  $model
     *
     * @return Model|null
     */
    public function transform(Data $data, ?Model $model = null) : ?Model
    {

        $model->title                   = $data->title;
        $model->image                   = $data->image;
        $model->description             = $data->description;
        $model->keywords                = $data->keywords;
        $model->category_id             = $data->categoryId ?? $model->category_id;
        $model->sort                    = $data->sort;
        $model->extension->content_type = $data->contentType;
        $model->extension->content      = $data->content;
        $model->setRelation('tags', collect($data->tags));
        return $model;

    }


}