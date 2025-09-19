<?php

namespace RedJasmine\Organization\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class MemberPositionData extends Data
{
    public int $memberId;
    public int $positionId;
    public ?string $startedAt = null;
    public ?string $endedAt = null;

    public static function attributes() : array
    {
        return [
            'member_id' => '成员ID',
            'position_id' => '职位ID',
            'started_at' => '任职开始时间',
            'ended_at' => '任职结束时间',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'member_id' => ['required', 'integer'],
            'position_id' => ['required', 'integer'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date', 'after:started_at'],
        ];
    }
}


