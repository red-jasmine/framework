<?php

namespace RedJasmine\Vip\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Vip\Domain\Models\VipProduct;

/** @mixin VipProduct */
class VipProductResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'              => (string)$this->id,
            'biz'          => $this->biz,
            'type'            => $this->type,
            'name'            => $this->name,
            'price'           => $this->price,
            'stock'           => $this->stock,
            'status'          => $this->status,
            'time_unit'       => $this->time_unit,
            'time_unit_label' => $this->time_unit->label(),
            'time_value'      => $this->time_value,
        ];
    }
}
