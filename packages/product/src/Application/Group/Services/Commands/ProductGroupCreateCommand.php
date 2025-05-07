<?php

namespace RedJasmine\Product\Application\Group\Services\Commands;

use RedJasmine\Product\Domain\Group\Models\Enums\GroupStatusEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Data\UserData;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProductGroupCreateCommand extends Data
{


    public UserData        $owner;
    public string          $name;
    public ?string         $description = null;
    public int             $parentId    = 0;
    public GroupStatusEnum $status      = GroupStatusEnum::ENABLE;
    public int             $sort        = 0;
    public bool            $isLeaf      = false;
    public bool            $isShow      = false;
    public string|null     $groupName   = null;
    public string|null     $image       = null;


    public static function attributes() : array
    {
        return [
            'parent_id'   => __('red-jasmine-product::product-category.fields.parent_id'),
            'name'        => __('red-jasmine-product::product-category.fields.name'),
            'group_name'  => __('red-jasmine-product::product-category.fields.group_name'),
            'sort'        => __('red-jasmine-product::product-category.fields.sort'),
            'is_leaf'     => __('red-jasmine-product::product-category.fields.is_leaf'),
            'is_show'     => __('red-jasmine-product::product-category.fields.is_show'),
            'status'      => __('red-jasmine-product::product-category.fields.status'),
            'description' => __('red-jasmine-product::product-category.fields.description'),
        ];
    }


    public static function rules(ValidationContext $context) : array
    {

        return [
            'id'          => [],
            'parent_id'   => [ 'integer' ],
            'name'        => [ 'required', 'string', 'max:100' ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'group_name'  => [ 'sometimes', 'nullable', 'max:100' ],
            'image'       => [ 'sometimes', 'nullable', 'max:255' ],
            'sort'        => [ 'integer' ],
            'is_leaf'     => [ 'required', 'boolean' ],
            'is_show'     => [ 'required', 'boolean' ],
            'status'      => [],
            'extra'     => [ 'sometimes', 'nullable', 'array' ],
        ];

    }


}
