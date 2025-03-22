<?php

namespace RedJasmine\Article\Domain\Data;

use RedJasmine\Article\Domain\Models\Enums\CategoryStatusEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ArticleCategoryData extends Data
{

    public string             $name;
    public ?string            $description = null;
    public int                $parentId    = 0;
    #[WithCast(EnumCast::class, CategoryStatusEnum::class)]
    public CategoryStatusEnum $status      = CategoryStatusEnum::ENABLE;
    public int                $sort        = 0;
    public bool               $isLeaf      = false;
    public bool               $isShow      = false;
    public ?string            $image       = null;
    public ?string            $cluster     = null;


    public static function rules(ValidationContext $context) : array
    {

        return [
            'id'          => [],
            'parent_id'   => ['integer'],
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['cluster', 'nullable', 'string', 'max:255'],
            'image'       => ['sometimes', 'nullable', 'max:255'],
            'extras'      => ['sometimes', 'nullable', 'array'],
        ];

    }


}