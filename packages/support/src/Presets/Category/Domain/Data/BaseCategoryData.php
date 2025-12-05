<?php

namespace RedJasmine\Support\Presets\Category\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class BaseCategoryData extends Data
{

    public ?string             $slug;
    public string              $name;
    public ?string             $description = null;
    public ?string             $cluster     = null;
    public int                 $parentId    = 0;
    #[WithCast(EnumCast::class, UniversalStatusEnum::class)]
    public UniversalStatusEnum $status      = UniversalStatusEnum::ENABLE;
    public int                 $sort        = 0;
    public bool                $isLeaf      = false;
    public bool                $isShow      = false;
    public ?string             $image       = null;
    public ?string             $icon        = null;
    public ?string             $color       = null;

    public ?array              $extra       = null;


    /**
     * @var BaseCategoryTranslationData[]|null
     */
    public ?array $translations = null;


    public static function attributes() : array
    {
        return [
            'parent_id'   => __('red-jasmine-support::category.fields.parent_id'),
            'name'        => __('red-jasmine-support::category.fields.name'),
            'slug'        => __('red-jasmine-support::category.fields.slug'),
            'description' => __('red-jasmine-support::category.fields.description'),
            'icon'        => __('red-jasmine-support::category.fields.icon'),
            'image'       => __('red-jasmine-support::category.fields.image'),
            'color'       => __('red-jasmine-support::category.fields.color'),
            'cluster'     => __('red-jasmine-support::category.fields.cluster'),
            'sort'        => __('red-jasmine-support::category.fields.sort'),
            'is_leaf'     => __('red-jasmine-support::category.fields.is_leaf'),
            'is_show'     => __('red-jasmine-support::category.fields.is_show'),
            'status'      => __('red-jasmine-support::category.fields.status'),
        ];
    }


    public static function rules(ValidationContext $context) : array
    {

        return [
            'parent_id'   => ['integer'],
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'cluster'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'image'       => ['sometimes', 'nullable', 'max:255'],
            'extra'       => ['sometimes', 'nullable', 'array'],
        ];

    }
}