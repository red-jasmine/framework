<?php

namespace RedJasmine\Payment\Domain\Transformer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Payment\Domain\Data\SettleReceiverData;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Models\ValueObjects\SettleReceiverAccount;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class SettleReceiverTransformer implements TransformerInterface
{
    public function transform(Data|SettleReceiverData $data, Model|SettleReceiver|null $model = null) : ?SettleReceiver
    {
        $model = $model ?? SettleReceiver::make();

        $model->system_merchant_app_id = $data->systemMerchantAppId;
        $model->name                   = $data->name;
        $model->relation_type          = $data->relationType;
        $model->cert_type              = $data->certType ?? null;
        $model->cert_no                = $data->certNo ?? null;

        $accounts = [];
        foreach ($data->accounts as $account) {
            $accounts[] = SettleReceiverAccount::make($account->toArray());
        }

        $model->setRelation('accounts', Collection::make($accounts));

        return $model;
    }


}
