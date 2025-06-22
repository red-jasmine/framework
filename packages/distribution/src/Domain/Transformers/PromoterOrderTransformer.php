<?php

namespace RedJasmine\Distribution\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Distribution\Domain\Data\PromoterOrderData;
use RedJasmine\Distribution\Domain\Models\PromoterOrder;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PromoterOrderTransformer implements TransformerInterface
{
    /**
     * @param  Data|PromoterOrderData  $data
     * @param  Model|PromoterOrder  $model
     *
     * @return PromoterOrder
     */
    public function transform($data, $model) : PromoterOrder
    {
        /**
         * @var PromoterOrder $model
         * @var PromoterOrderData $data
         */
        $model->promoter_id = $data->promoterId;
        $model->order_id = $data->orderId;
        $model->order_type = $data->orderType;
        $model->order_amount = $data->orderAmount;
        $model->commission_amount = $data->commissionAmount;
        $model->commission_ratio = $data->commissionRatio;
        $model->order_time = $data->orderTime;
        $model->settlement_time = $data->settlementTime;
        $model->remarks = $data->remarks;
        
        // 设置用户信息
        if (isset($data->user)) {
            $model->user_type = $data->user->getType();
            $model->user_id = $data->user->getID();
        }

        return $model;
    }
}