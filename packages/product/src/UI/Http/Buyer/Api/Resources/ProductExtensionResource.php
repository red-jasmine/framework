<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\Extensions\ProductExtension */
class ProductExtensionResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'               => $this->id,
            'basic_props'      => $this->basic_props,
            'sale_props'       => $this->sale_props,
            'keywords'         => $this->keywords,
            'description'      => $this->description,
            'images'           => $this->images,
            'videos'           => $this->videos,
            'detail'           => $this->detail,
            'weight'           => $this->weight,
            'width'            => $this->width,
            'height'           => $this->height,
            'length'           => $this->length,
            'size'             => $this->size,
            'tools'            => $this->tools,
            'extra'          => $this->extra,

        ];
    }
}
