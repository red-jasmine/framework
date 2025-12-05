<?php

namespace RedJasmine\Organization\Domain\Data;

use RedJasmine\Organization\Domain\Models\Enums\MemberStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class MemberData extends Data
{
    public int $orgId = 0;
    public string $memberNo;
    public string $name;
    public ?string $nickname = null;
    public ?string $avatar = null;
    public ?string $mobile = null;
    public ?string $email = null;
    public ?string $gender = null;
    public ?string $telephone = null;
    public ?string $hiredAt = null;
    public ?string $resignedAt = null;
    #[WithCast(EnumCast::class, MemberStatusEnum::class)]
    public MemberStatusEnum $status = MemberStatusEnum::ACTIVE;
    public ?string $positionName = null;
    public ?int $positionLevel = null;
    public ?int $mainDepartmentId = null;
    public ?array $departments = null;

    public static function attributes() : array
    {
        return [
            'org_id' => '组织ID',
            'member_no' => '成员编号',
            'name' => '姓名',
            'nickname' => '昵称',
            'avatar' => '头像',
            'mobile' => '手机号',
            'email' => '邮箱',
            'gender' => '性别',
            'telephone' => '座机',
            'hired_at' => '入职时间',
            'resigned_at' => '离职时间',
            'status' => '状态',
            'position_name' => '主职位名称',
            'position_level' => '主职位级别',
            'main_department_id' => '主部门ID',
            'departments' => '有效部门集合',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'org_id' => ['required', 'integer'],
            'member_no' => ['required', 'string', 'max:64'],
            'name' => ['required', 'string', 'max:100'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'gender' => ['nullable', 'string', 'max:20'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'hired_at' => ['nullable', 'date'],
            'resigned_at' => ['nullable', 'date', 'after:hired_at'],
            'status' => ['required'],
            'position_name' => ['nullable', 'string', 'max:100'],
            'position_level' => ['nullable', 'integer', 'min:0'],
            'main_department_id' => ['nullable', 'integer'],
            'departments' => ['nullable', 'array'],
        ];
    }
}


