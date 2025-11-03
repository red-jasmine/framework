<?php

namespace RedJasmine\Product\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\ProductVariant */
class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'properties'      => $this->properties,
            'attrs_name' => $this->attrs_name,
            'image'           => $this->image,
            'barcode'         => $this->barcode,
            'status'          => $this->status,
            'price'           => (string)$this->price,
            'market_price'    => (string)$this->market_price,
            'cost_price'      => (string)$this->cost_price,
            'sales'           => $this->sales,
            'stock'           => $this->stock,
            'safety_stock'    => $this->safety_stock,
            'version'         => $this->version
        ];
    }


}
