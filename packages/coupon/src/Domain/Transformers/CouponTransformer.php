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
        $model->name                 = $data->name;
        $model->description          = $data->description;
        $model->image                = $data->image;
        $model->discount_target      = $data->discountTarget;
        $model->threshold_type       = $data->thresholdType;
        $model->threshold_value      = $data->thresholdValue;
        $model->discount_amount_type = $data->discountAmountType;
        $model->discount_amount_type = $data->discountAmountValue;

        $model->max_discount_amount = $data->maxDiscountAmount;

        $model->status = $data->status;

        $model->validity_type       = $data->validityType;
        $model->validity_start_time = $data->validityStartTime;
        $model->validity_end_time   = $data->validityEndTime;

        $model->delayed_effective_time_type  = $data->delayedEffectiveTimeType;
        $model->delayed_effective_time_value = $data->delayedEffectiveTimeValue;
        $model->validity_time_type           = $data->validityTimeType;
        $model->validity_time_value          = $data->validityTimeValue;


        $model->usage_rules   = $data->usageRule;
        $model->receive_rules = $data->receiveRules;

        $model->total_quantity = $data->totalQuantity;
        $model->is_show        = $data->isShow;
        $model->sort           = $data->sort;
        $model->remarks        = $data->remarks;


        return $model;
    }
}