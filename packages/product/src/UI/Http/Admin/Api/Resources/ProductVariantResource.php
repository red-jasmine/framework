<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Product\Models\ProductVariant;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin ProductVariant */
class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'             => $this->id,
            'attrs_sequence' => $this->attrs_sequence,
            'attrs_name'     => $this->attrs_name,
            'sku'            => $this->sku,
            'image'          => $this->image,
            'barcode'        => $this->barcode,
            'status'         => $this->status,
            'price'          => (string) $this->price,
            'market_price'   => (string) $this->market_price,
            'cost_price'     => (string) $this->cost_price,
            'sales'          => $this->sales,
            'stock'          => $this->stock,
            'safety_stock'   => $this->safety_stock,
            'version'        => $this->version,
            'modified_time'  => $this->modified_time,
            'creator_type'   => $this->creator_type,
            'creator_id'     => $this->creator_id,
            'updater_type'   => $this->updater_type,
            'updater_id'     => $this->updater_id,
        ];
    }


}
