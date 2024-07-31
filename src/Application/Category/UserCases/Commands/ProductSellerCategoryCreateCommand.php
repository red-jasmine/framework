<?php

namespace RedJasmine\Product\Application\Category\UserCases\Commands;

use RedJasmine\Product\Domain\Category\Enums\CategoryStatusEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProductSellerCategoryCreateCommand extends Data
{


    public UserData           $owner;
    public string             $name;
    public int                $parentId  = 0;
    public CategoryStatusEnum $status    = CategoryStatusEnum::ENABLE;
    public int                $sort      = 0;
    public bool               $isLeaf    = false;
    public bool               $isShow    = false;
    public string|null        $groupName = null;
    public string|null        $image     = null;



    public static function attributes() : array
    {
        return [
            'parent_id'  => __('red-jasmine/product::product-category.fields.parent_id'),
            'name'       => __('red-jasmine/product::product-category.fields.name'),
            'group_name' => __('red-jasmine/product::product-category.fields.group_name'),
            'sort'       => __('red-jasmine/product::product-category.fields.sort'),
            'is_leaf'    => __('red-jasmine/product::product-category.fields.is_leaf'),
            'is_show'    => __('red-jasmine/product::product-category.fields.is_show'),
            'status'     => __('red-jasmine/product::product-category.fields.status'),
            'expands'    => __('red-jasmine/product::product-category.fields.extends'),
        ];
    }


    public static function rules(ValidationContext $context) : array
    {

        return [
            'id'         => [],
            'parent_id'  => [ 'integer' ],
            'name'       => [ 'required', 'string', 'max:100' ],
            'group_name' => [ 'sometimes', 'nullable', 'max:100' ],
            'image'      => [ 'sometimes', 'nullable', 'max:255' ],
            'sort'       => [ 'integer' ],
            'is_leaf'    => [ 'required', 'boolean' ],
            'is_show'    => [ 'required', 'boolean' ],
            'status'     => [],
            'expands'    => [ 'sometimes', 'nullable', 'array' ],
        ];

    }


}
