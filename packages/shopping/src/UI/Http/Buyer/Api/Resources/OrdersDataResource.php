<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Ecommerce\Domain\Data\OrdersData;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin OrdersData
 */
class OrdersDataResource extends JsonResource
{
    public function toArray(Request $request) : array
    {

        return [
            'total'  => $this->total,
            'count'  => $this->count,
            'orders' => OrderDataResource::collection($this->orders),
        ];
    }
}