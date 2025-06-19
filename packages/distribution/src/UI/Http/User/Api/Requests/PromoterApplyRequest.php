<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoterApplyRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'level' => 'sometimes|integer|min:1',
            'parent_id' => 'sometimes|integer|min:0',
            'remarks' => 'sometimes|nullable|string|max:500',
        ];
    }

    public function messages() : array
    {
        return [

            'level.integer' => '等级必须是整数',
            'level.min' => '等级不能小于1',
            'parent_id.integer' => '上级ID必须是整数',
            'parent_id.min' => '上级ID不能小于0',
            'group_id.integer' => '分组ID必须是整数',
            'group_id.exists' => '分组不存在',
            'team_id.integer' => '团队ID必须是整数',
            'team_id.exists' => '团队不存在',
        ];
    }
}
