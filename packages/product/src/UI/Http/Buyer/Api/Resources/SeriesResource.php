<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin  ProductSeries
 */
class SeriesResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'id'       => (string)$this->id,
            'name'     => $this->name,
            'products' => SeriesProductResource::collection($this->whenLoaded('products'))
        ];
    }
}