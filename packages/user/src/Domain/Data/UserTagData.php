<?php

namespace RedJasmine\User\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\User\Domain\Enums\UserTagStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserTagData extends Data
{
    public string            $name;
    public ?string           $description = null;
    public ?int              $categoryId  = null;
    #[WithCast(EnumCast::class, UserTagStatusEnum::class)]
    public UserTagStatusEnum $status      = UserTagStatusEnum::ENABLE;
    public int               $sort        = 0;
    public ?string           $icon        = null;
    public ?string           $cluster     = null;
    public ?array            $extra       = null;


    public static function rules(ValidationContext $context) : array
    {

        return [
            'id'          => [],
            'category_id' => ['sometimes', 'integer'],
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'cluster'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'icon'        => ['sometimes', 'nullable', 'max:255'],
            'extra'       => ['sometimes', 'nullable', 'array'],
        ];

    }

}