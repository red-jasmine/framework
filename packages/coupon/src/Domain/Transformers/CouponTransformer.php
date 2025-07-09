<?php

namespace RedJasmine\Coupon\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Coupon\Domain\Data\CouponData;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class CouponTransformer implements TransformerInterface
{
    /**
     * @param CouponData $data
     * @param Coupon $model
     * @return Coupon
     */
    public function transform($data, $model): Coupon
    {
        /**
         * @var Coupon $model
         * @var CouponData $data
         */
        $model->name = $data->name;
        $model->description = $data->description;
        $model->image = $data->image;
        $model->status = $data->status;
        $model->discount_type = $data->discountType;
        $model->discount_value = $data->discountValue;
        $model->max_discount_amount = $data->maxDiscountAmount;
        $model->is_ladder = $data->isLadder;
        $model->ladder_rules = $data->ladderRules;
        $model->threshold_type = $data->thresholdType;
        $model->threshold_value = $data->thresholdValue;
        $model->is_threshold_required = $data->isThresholdRequired;
        $model->validity_type = $data->validityType;
        $model->start_time = $data->startTime;
        $model->end_time = $data->endTime;
        $model->relative_days = $data->relativeDays;
        $model->max_usage_per_user = $data->maxUsagePerUser;
        $model->max_usage_total = $data->maxUsageTotal;
        $model->usage_rules = $data->usageRule;
        $model->collect_rules = $data->collectRule;
        $model->cost_bearer_type = $data->costBearerType;
        $model->cost_bearer_id = $data->costBearerId;
        $model->cost_bearer_name = $data->costBearerName;
        $model->issue_strategy = $data->issueStrategy;
        $model->total_issue_limit = $data->totalIssueLimit;
        $model->current_issue_count = $data->currentIssueCount;

        return $model;
    }
}