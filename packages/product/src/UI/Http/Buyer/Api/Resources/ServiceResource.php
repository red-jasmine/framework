<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin  ProductService
 */
class ServiceResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'cluster'     => $this->cluster,
            'sort'        => $this->sort,
            'status'      => $this->status,
            'is_show'     => $this->is_show,
        ];
    }

}