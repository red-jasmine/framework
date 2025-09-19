<?php

namespace RedJasmine\Organization\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class MemberDepartmentData extends Data
{
    public int $memberId;
    public int $departmentId;
    public bool $isPrimary = false;
    public ?string $startedAt = null;
    public ?string $endedAt = null;

    public static function attributes() : array
    {
        return [
            'member_id' => '成员ID',
            'department_id' => '部门ID',
            'is_primary' => '是否主部门',
            'started_at' => '任职开始时间',
            'ended_at' => '任职结束时间',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'member_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'is_primary' => ['boolean'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date', 'after:started_at'],
        ];
    }
}


