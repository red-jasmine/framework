<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\Extensions\ProductExtension */
class ProductExtensionResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'               => $this->id,
            'basic_attrs'      => $this->basic_attrs,
            'sale_attrs'       => $this->sale_attrs,
            'keywords'         => $this->keywords,
            'description'      => $this->description,
            'images'           => $this->images,
            'videos'           => $this->videos,
            'description'           => $this->description,
            'weight'           => $this->weight,
            'width'            => $this->width,
            'height'           => $this->height,
            'length'           => $this->length,
            'size'             => $this->size,
            'remarks'          => $this->remarks,
            'tools'            => $this->tools,
            'extra'          => $this->extra,

        ];
    }
}
