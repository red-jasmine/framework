<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\ProductVariant */
class ProductSkuResource extends JsonResource
{
    public function toArray(Request $request)
    {

        return [
            'id'                  => $this->id,
            'attrs_sequence' => $this->attrs_sequence,
            'attrs_name'     => $this->attrs_name,
            'image'               => $this->image,
            'barcode'             => $this->barcode,
            'status'              => $this->status,
            'weight'              => $this->weight,
            'width'               => $this->width,
            'height'              => $this->height,
            'length'              => $this->length,
            'size'                => $this->size,
            'price'               => (string)$this->price,
            'market_price'        => $this->market_price?->value(),
            'cost_price'          => $this->cost_price?->value(),
            'sales'               => $this->sales,
            'stock'               => $this->stock,
            'safety_stock'        => $this->safety_stock,
            'version'             => $this->version,
            'modified_at'       => $this->modified_at,
            'creator_type'        => $this->creator_type,
            'creator_id'          => $this->creator_id,
            'updater_type'        => $this->updater_type,
            'updater_id'          => $this->updater_id,
        ];
    }


}
