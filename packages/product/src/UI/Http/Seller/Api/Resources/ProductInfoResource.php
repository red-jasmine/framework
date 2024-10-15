<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\ProductInfo */
class ProductInfoResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [

            'id'               => $this->id,
            'promise_services' => $this->promise_services,
            'tips'             => $this->tips,
            'keywords'         => $this->keywords,
            'description'      => $this->description,
            'images'           => $this->images,
            'videos'           => $this->videos,
            'detail'           => $this->detail,
            'remarks'          => $this->remarks,
            'tools'            => $this->tools,
            'expands'          => $this->expands,
            'basic_props'      => $this->basic_props,
            'sale_props'       => $this->sale_props,
            'customize_props'  => $this->customize_props,
            'form'             => $this->form,
        ];
    }
}
