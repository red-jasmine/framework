<?php

namespace RedJasmine\Organization\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use RedJasmine\Organization\Domain\Models\Enums\OrganizationStatusEnum;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class OrganizationData extends Data
{
    public ?int $parentId = null;
    public string $name;
    public ?string $shortName = null;
    public ?string $code = null;
    public int $sort = 0;
    #[WithCast(EnumCast::class, OrganizationStatusEnum::class)]
    public OrganizationStatusEnum $status = OrganizationStatusEnum::ENABLE;

    public static function attributes() : array
    {
        return [
            'parent_id' => '父级组织',
            'name' => '组织名称',
            'short_name' => '组织简称',
            'code' => '组织编码',
            'sort' => '排序',
            'status' => '状态',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'parent_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:100'],
            'short_name' => ['nullable', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'sort' => ['integer', 'min:0'],
            'status' => ['required'],
        ];
    }
}


