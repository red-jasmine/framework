<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/**
 * @mixin \RedJasmine\Product\Domain\Category\Models\ProductCategory
 */
class CategoryResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [

            'id'          => $this->id,
            'parent_id'   => $this->parent_id,
            'name'        => $this->name,
            'group_name'  => $this->group_name,
            'sort'        => $this->sort,
            'is_leaf'     => $this->is_leaf,
            'is_show'     => $this->is_show,
            'status'      => $this->status,
            'expands' => $this->expands,
            'children'    => $this->children ? static::collection(collect($this->children)) : null,
        ];
    }
}
