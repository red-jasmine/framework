<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use RedJasmine\Product\Domain\Stock\Models\ProductStockLog;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin  ProductStockLog
 */
class StockLogResource extends JsonResource
{


    public function toArray($request) : array
    {

        return [
            'id'            => $this->id,
            'product_id'    => $this->product_id,
            'sku_id'        => $this->sku_id,
            'owner_type'    => $this->owner_type,
            'owner_id'      => $this->owner_id,
            'type'          => $this->type,
            'stock'         => $this->stock,
            'lock_stock'    => $this->lock_stock,
            'channel_type'  => $this->channel_type,
            'channel_id'    => $this->channel_id,
            'change_type'   => $this->change_type,
            'change_detail' => $this->change_detail,
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
            'creator_id'    => $this->creator_id,
            'creator_type'  => $this->creator_type,
            'extras'       => $this->extras,
            'product'       => new StockProductResource($this->whenLoaded('product')),
            'sku'           => new StockSkuResource($this->whenLoaded('sku')),

        ];
    }

}
