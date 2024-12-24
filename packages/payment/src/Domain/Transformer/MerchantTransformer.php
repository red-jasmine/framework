<?php

namespace RedJasmine\Payment\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Payment\Domain\Data\MerchantData;
use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class MerchantTransformer implements TransformerInterface
{

    public function transform(
        MerchantData|Data $data,
        Merchant|Model|null $merchant = null
    ) : Merchant {

        $merchant = $merchant ?? Merchant::make();

        $merchant->owner      = $data->owner;
        $merchant->name       = $data->name;
        $merchant->short_name = $data->shortName;
        $merchant->type       = $data->type;
        $merchant->isv_id     = $data->isvId;
        $merchant->status     = $data->status;


        return $merchant;
    }
}
