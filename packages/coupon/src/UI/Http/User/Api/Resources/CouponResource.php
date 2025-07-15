<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin Coupon
 */
class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray(Request $request) : array
    {
        return [
            'id' => $this->id,

            'name'                  => $this->name,
            'description'           => $this->description,
            'label'                 => $this->label,
            'image'                 => $this->image,
            'is_show'               => $this->is_show,
            'status'                => $this->status,
            'discount_level'       => $this->discount_level,
            'threshold_type'        => $this->threshold_type,
            'threshold_value'       => $this->threshold_value,
            'discount_amount_type'  => $this->discount_amount_type,
            'discount_amount_value' => $this->discount_amount_value,
            'max_discount_amount'   => $this->max_discount_amount,
            'usage_rules'           => $this->usage_rules,
            'receive_rules'         => $this->receive_rules,
        ];
    }
} 