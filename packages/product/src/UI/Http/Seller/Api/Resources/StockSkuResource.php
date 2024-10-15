<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductSku
 */
class StockSkuResource extends JsonResource
{


    public function toArray($request) : array
    {
        return [
            'id'              => $this->id,
            'product_id'      => $this->product_id,
            'owner_type'      => $this->owner_type,
            'owner_id'        => $this->owner_id,
            'stock'           => $this->stock,
            'status'          => $this->status,
            'lock_stock'      => $this->lock_stock,
            'safety_stock'    => $this->safety_stock,
            'properties'      => $this->properties,
            'properties_name' => $this->properties_name,
            'product'         => new StockProductResource($this->whenLoaded('product'))
        ];
    }
}
