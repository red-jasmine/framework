<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoterApplyRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'name' => 'required|string|max:100',
            'level' => 'sometimes|integer|min:1',
            'parent_id' => 'sometimes|integer|min:0',
            'group_id' => 'sometimes|nullable|integer|exists:promoter_groups,id',
            'team_id' => 'sometimes|nullable|integer|exists:promoter_teams,id',
            'remarks' => 'sometimes|nullable|string|max:500',
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => '推广员名称不能为空',
            'name.string' => '推广员名称必须是字符串',
            'name.max' => '推广员名称不能超过100个字符',
            'level.integer' => '等级必须是整数',
            'level.min' => '等级不能小于1',
            'parent_id.integer' => '上级ID必须是整数',
            'parent_id.min' => '上级ID不能小于0',
            'group_id.integer' => '分组ID必须是整数',
            'group_id.exists' => '分组不存在',
            'team_id.integer' => '团队ID必须是整数',
            'team_id.exists' => '团队不存在',
            'remarks.string' => '备注必须是字符串',
            'remarks.max' => '备注不能超过500个字符',
        ];
    }
}