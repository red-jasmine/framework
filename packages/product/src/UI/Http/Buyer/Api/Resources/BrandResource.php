<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/**
 * @mixin \RedJasmine\Product\Domain\Brand\Models\Brand
 */
class BrandResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'parent_id'    => $this->parent_id,
            'name'         => $this->name,
            'english_name' => $this->english_name,
            'is_show'      => $this->is_show,
            'logo'         => $this->logo,
            'status'       => $this->status,
            'expands'      => $this->expands,
        ];
    }
}
