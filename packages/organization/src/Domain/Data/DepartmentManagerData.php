<?php

namespace RedJasmine\Organization\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class DepartmentManagerData extends Data
{
    public int $departmentId;
    public int $memberId;
    public bool $isPrimary = false;
    public ?string $startedAt = null;
    public ?string $endedAt = null;

    public static function attributes() : array
    {
        return [
            'department_id' => '部门ID',
            'member_id' => '成员ID',
            'is_primary' => '是否主要负责人',
            'started_at' => '任命开始时间',
            'ended_at' => '任命结束时间',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'department_id' => ['required', 'integer'],
            'member_id' => ['required', 'integer'],
            'is_primary' => ['boolean'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date', 'after:started_at'],
        ];
    }
}


