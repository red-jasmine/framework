<?php

namespace RedJasmine\Announcement\Domain\Transformers;

use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class CategoryTransformer extends \RedJasmine\Support\Domain\Transformer\CategoryTransformer implements TransformerInterface
{
    /**
     * @param  BaseCategoryData  $data
     * @param  BaseCategoryModel  $model
     *
     * @return BaseCategoryModel
     */
    public function transform($data, $model) : BaseCategoryModel
    {
        $model      = parent::transform($data, $model);
        $model->biz = $data->biz;

        return $model;
    }


}
