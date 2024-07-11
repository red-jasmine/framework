<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/**
 * @mixin ProductSeries
 */
class SeriesResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'owner_id'     => $this->owner_id,
            'owner_type'   => $this->owner_type,
            'remarks'      => $this->remarks,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at?->format('Y-m-d H:i:s'),
            'creator_type' => $this->creator_type,
            'creator_id'   => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id'   => $this->updater_id,
            'product'      => SeriesProductResource::collection($this->whenLoaded('products')),

        ];
    }

}
