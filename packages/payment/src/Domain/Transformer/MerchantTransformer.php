<?php

namespace RedJasmine\Payment\Domain\Transformer;

use RedJasmine\Payment\Domain\Data\MerchantData;
use RedJasmine\Payment\Domain\Models\Merchant;

class MerchantTransformer
{

    public function transform(MerchantData $data, ?Merchant $merchant = null) : Merchant
    {

        $merchant = $merchant ?? Merchant::newModel();

        $merchant->owner      = $data->owner;
        $merchant->name       = $data->name;
        $merchant->short_name = $data->shortName;
        $merchant->type       = $data->type;
        $merchant->isv_id     = $data->isvId;
        $merchant->status     = $data->status;

        return $merchant;
    }
}
