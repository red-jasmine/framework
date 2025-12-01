<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Series\Models\ProductSeriesProduct;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductSeriesProduct
 */
class SeriesProductResource extends JsonResource
{

    public function toArray(Request $request) : array
    {
        return [
            'product_id' => $this->product_id,
            'position'   => $this->position,
        ];
    }

}
