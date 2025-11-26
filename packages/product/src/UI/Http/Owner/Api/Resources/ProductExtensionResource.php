<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Resources;

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
            'description'           => $this->description,
            'remarks'          => $this->remarks,
            'tools'            => $this->tools,
            'extra'          => $this->extra,
            'basic_attrs'      => $this->basic_attrs,
            'sale_attrs'       => $this->sale_attrs,
            'customize_attrs'  => $this->customize_attrs,
            'form'             => $this->form,
        ];
    }
}
