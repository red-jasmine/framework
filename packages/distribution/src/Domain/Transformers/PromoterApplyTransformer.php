<?php

namespace RedJasmine\Distribution\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Domain\Data\PromoterApplyData;
use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Foundation\Data\Data;

class PromoterApplyTransformer implements TransformerInterface
{
    /**
     * @param  Data|PromoterApplyData  $data
     * @param  Model|PromoterApply  $model
     *
     * @return PromoterApply
     */
    public function transform($data, $model) : PromoterApply
    {
        /**
         * @var PromoterApply $model
         * @var PromoterApplyData $data
         */
        $model->apply_type = $data->applyType;
        $model->level = $data->level;
        $model->apply_method = $data->applyMethod;

        return $model;
    }
}