<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\ProductSku */
class ProductSkuResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'properties'      => $this->properties,
            'properties_name' => $this->properties_name,
            'image'           => $this->image,
            'barcode'         => $this->barcode,
            'status'          => $this->status,
            'supplier_sku_id' => $this->supplier_sku_id,
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
