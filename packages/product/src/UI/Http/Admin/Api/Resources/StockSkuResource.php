<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use RedJasmine\Product\Domain\Stock\Models\ProductVariant;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductVariant
 */
class StockSkuResource extends JsonResource
{


    public function toArray($request) : array
    {
        return [
            'id'           => $this->id,
            'product_id'   => $this->product_id,
            'stock'        => $this->stock,
            'status'       => $this->status,
            'lock_stock'   => $this->lock_stock,
            'safety_stock' => $this->safety_stock,
            'properties'   => $this->properties,
            'attrs_name'   => $this->attrs_name,
            'product'      => new StockProductResource($this->whenLoaded('product'))
        ];
    }
}
