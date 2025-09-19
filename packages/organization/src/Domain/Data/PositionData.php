<?php

namespace RedJasmine\Organization\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use RedJasmine\Organization\Domain\Models\Enums\PositionStatusEnum;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class PositionData extends Data
{
    public string $name;
    public ?string $code = null;
    public ?string $sequence = null;
    public ?int $level = null;
    public ?int $parentId = null;
    public ?string $description = null;
    #[WithCast(EnumCast::class, PositionStatusEnum::class)]
    public PositionStatusEnum $status = PositionStatusEnum::ENABLE;

    public static function attributes() : array
    {
        return [
            'name' => '职位名称',
            'code' => '职位编码',
            'sequence' => '职位序列',
            'level' => '职级',
            'parent_id' => '父级职位',
            'description' => '职位描述',
            'status' => '状态',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'sequence' => ['nullable', 'string', 'max:50'],
            'level' => ['nullable', 'integer', 'min:0'],
            'parent_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'status' => ['required'],
        ];
    }
}


