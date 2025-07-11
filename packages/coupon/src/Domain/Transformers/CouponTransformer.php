<?php

namespace RedJasmine\Coupon\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Coupon\Domain\Data\CouponData;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class CouponTransformer implements TransformerInterface
{
    /**
     * @param  CouponData  $data
     * @param  Coupon  $model
     *
     * @return Coupon
     */
    public function transform($data, $model) : Coupon
    {

        /**
         * @var Coupon $model
         * @var CouponData $data
         */
        $model->name                   = $data->name;
        $model->description            = $data->description;
        $model->image                  = $data->image;
        $model->is_show                = $data->isShow;
        $model->status                 = $data->status;
        $model->owner                  = $data->owner;
        $model->discount_target        = $data->discountTarget;
        $model->discount_amount_type   = $data->discountAmountType;
        $model->discount_amount_value  = $data->discountAmountValue;
        $model->threshold_type         = $data->thresholdType;
        $model->threshold_value        = $data->thresholdValue;
        $model->max_discount_amount    = $data->maxDiscountAmount;
        $model->validity_type          = $data->validityType;
        $model->validity_start_time    = $data->validityStartTime;
        $model->validity_end_time      = $data->validityEndTime;
        $model->delayed_effective_time = $data->delayedEffectiveTime;
        $model->validity_time          = $data->validityTime;
        $model->usage_rules            = $data->usageRules;
        $model->receive_rules          = $data->receiveRules;
        $model->start_time             = $data->startTime;
        $model->end_time               = $data->endTime;
        $model->sort                   = $data->sort;
        $model->remarks                = $data->remarks;
        $model->total_quantity         = $data->totalQuantity;
        $model->cost_bearer            = $data->costBearer;


        return $model;
    }


    protected function validate($data, $model)
    {
        /**
         * @var Coupon $model
         * @var CouponData $data
         */
      

    }
}