<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\Extensions\ProductExtension */
class ProductExtensionResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [

            'id'               => $this->id,

            'tips'             => $this->tips,
            'keywords'         => $this->keywords,
            'description'      => $this->description,
            'images'           => $this->images,
            'videos'           => $this->videos,
            'detail'           => $this->detail,
            'remarks'          => $this->remarks,
            'tools'            => $this->tools,
            'extra'          => $this->extra,
            'basic_props'      => $this->basic_props,
            'sale_props'       => $this->sale_props,
            'customize_props'  => $this->customize_props,
            'form'             => $this->form,
        ];
    }
}
