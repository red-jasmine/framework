<?php

namespace RedJasmine\Coupon\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Coupon\Domain\Data\UserCouponData;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class UserCouponTransformer implements TransformerInterface
{
    /**
     * @param UserCouponData $data
     * @param UserCoupon $model
     *
     * @return UserCoupon
     */
    public function transform($data, $model): UserCoupon
    {
        /**
         * @var UserCoupon $model
         * @var UserCouponData $data
         */
        $model->coupon_id = $data->couponId;
        $model->owner_type = $data->ownerType;
        $model->owner_id = $data->ownerId;
        $model->coupon_no = $data->couponNo;
        $model->status = $data->status;

        // 设置用户信息
        if ($data->user) {
            $model->user_type = $data->user->getType();
            $model->user_id = $data->user->getID();
        }

        $model->issue_time = $data->issueTime;
        $model->expire_time = $data->expireTime;
        $model->used_time = $data->usedTime;
        $model->order_id = $data->orderId;

        return $model;
    }
} 