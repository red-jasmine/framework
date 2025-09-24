<?php

namespace RedJasmine\Product\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/**
 * @mixin Brand
 */
class BrandResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'id'        => $this->id,
            'parent_id' => $this->parent_id,
            'name'      => $this->name,
            'is_show'   => $this->is_show,
            'logo'      => $this->logo,
            'status'    => $this->status,
            'extra'     => $this->extra,
        ];
    }
}
