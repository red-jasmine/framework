<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_no' => ['sometimes', 'string', 'max:64'],
            'name' => ['sometimes', 'string', 'max:100'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'gender' => ['nullable', 'string', 'max:20'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'hired_at' => ['nullable', 'date'],
            'resigned_at' => ['nullable', 'date', 'after:hired_at'],
            'status' => ['sometimes', 'string'],
            'position_name' => ['nullable', 'string', 'max:100'],
            'position_level' => ['nullable', 'integer', 'min:0'],
            'main_department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['integer', 'exists:departments,id'],
            'leader_id' => ['nullable', 'integer', 'exists:members,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'member_no.max' => '成员编号长度不能超过64个字符',
            'name.max' => '姓名长度不能超过100个字符',
            'nickname.max' => '昵称长度不能超过100个字符',
            'avatar.max' => '头像URL长度不能超过255个字符',
            'mobile.max' => '手机号长度不能超过20个字符',
            'email.email' => '邮箱格式不正确',
            'email.max' => '邮箱长度不能超过120个字符',
            'gender.max' => '性别长度不能超过20个字符',
            'telephone.max' => '座机长度不能超过50个字符',
            'hired_at.date' => '入职时间格式不正确',
            'resigned_at.date' => '离职时间格式不正确',
            'resigned_at.after' => '离职时间必须晚于入职时间',
            'position_name.max' => '职位名称长度不能超过100个字符',
            'position_level.integer' => '职位级别必须是整数',
            'position_level.min' => '职位级别不能小于0',
            'main_department_id.exists' => '主部门不存在',
            'departments.array' => '部门集合必须是数组',
            'departments.*.integer' => '部门ID必须是整数',
            'departments.*.exists' => '部门不存在',
            'leader_id.exists' => '上级不存在',
        ];
    }
}
