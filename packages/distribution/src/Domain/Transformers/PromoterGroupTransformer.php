<?php

namespace RedJasmine\Distribution\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Domain\Data\PromoterGroupData;
use RedJasmine\Distribution\Domain\Models\PromoterGroup;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Foundation\Data\Data;

class PromoterGroupTransformer implements TransformerInterface
{
    /**
     * @param  Data|PromoterGroupData  $data
     * @param  Model|PromoterGroup  $model
     *
     * @return PromoterGroup
     */
    public function transform($data, $model) : PromoterGroup
    {
        /**
         * @var PromoterGroup $model
         * @var PromoterGroupData $data
         */
        $model->name = $data->name;
        $model->description = $data->description;
        $model->sort = $data->sort;
        $model->status = $data->status;
        $model->parent_id = $data->parentId;

        return $model;
    }
}