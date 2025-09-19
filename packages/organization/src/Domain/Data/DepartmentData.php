<?php

namespace RedJasmine\Organization\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use RedJasmine\Organization\Domain\Models\Enums\DepartmentStatusEnum;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class DepartmentData extends Data
{
    public int $orgId = 0;
    public ?int $parentId = null;
    public string $name;
    public ?string $shortName = null;
    public ?string $code = null;
    public int $sort = 0;
    #[WithCast(EnumCast::class, DepartmentStatusEnum::class)]
    public DepartmentStatusEnum $status = DepartmentStatusEnum::ENABLE;

    public static function attributes() : array
    {
        return [
            'org_id' => '组织ID',
            'parent_id' => '父级部门',
            'name' => '部门名称',
            'short_name' => '部门简称',
            'code' => '部门编码',
            'sort' => '排序',
            'status' => '状态',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'org_id' => ['required', 'integer'],
            'parent_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:100'],
            'short_name' => ['nullable', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'sort' => ['integer', 'min:0'],
            'status' => ['required'],
        ];
    }
}


