<?php

namespace RedJasmine\Organization\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Data\MemberData;
use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class MemberTransformer implements TransformerInterface
{
    /** @param MemberData $data */
    public function transform($data, $model) : Model
    {
        if (!$model instanceof Member) {
            $model = new Member();
        }

        $model->org_id = $data->orgId;
        $model->member_no = $data->memberNo;
        $model->name = $data->name;
        $model->nickname = $data->nickname;
        $model->avatar = $data->avatar;
        $model->mobile = $data->mobile;
        $model->email = $data->email;
        $model->gender = $data->gender;
        $model->telephone = $data->telephone;
        $model->hired_at = $data->hiredAt;
        $model->resigned_at = $data->resignedAt;
        $model->status = $data->status;
        $model->position_name = $data->positionName;
        $model->position_level = $data->positionLevel;
        $model->main_department_id = $data->mainDepartmentId;
        $model->departments = $data->departments;

        return $model;
    }
}


