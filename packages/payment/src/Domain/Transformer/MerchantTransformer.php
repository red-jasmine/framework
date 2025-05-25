<?php

namespace RedJasmine\Payment\Domain\Transformer;

use RedJasmine\Payment\Domain\Data\MerchantData;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class MerchantTransformer implements TransformerInterface
{

    /**
     * @param  MerchantData  $data
     * @param  Merchant  $model
     *
     * @return Merchant
     */
    public function transform(
        $data,
        $model = null
    ) : Merchant {


        $model->owner      = $data->owner;
        $model->name       = $data->name;
        $model->short_name = $data->shortName;
        $model->type       = $data->type;
        $model->isv_id     = $data->isvId;
        $model->status     = $data->status;


        return $model;
    }
}
