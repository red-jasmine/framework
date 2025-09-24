<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Resources;

use RedJasmine\Product\Domain\Stock\Models\Product;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class StockProductResource extends JsonResource
{


    public function toArray($request) : array
    {
        return [
            'id'         => $this->id,
            'owner_type' => $this->owner_type,
            'owner_id'   => $this->owner_id,
            'title'      => $this->title,
            'image'      => $this->image,
            'status'     => $this->status,
        ];
    }
}
