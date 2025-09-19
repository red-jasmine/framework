<?php

namespace RedJasmine\Organization\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Data\MemberDepartmentData;
use RedJasmine\Organization\Domain\Models\MemberDepartment;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class MemberDepartmentTransformer implements TransformerInterface
{
    /** @param MemberDepartmentData $data */
    public function transform($data, $model) : Model
    {
        if (!$model instanceof MemberDepartment) {
            $model = new MemberDepartment();
        }

        $model->member_id = $data->memberId;
        $model->department_id = $data->departmentId;
        $model->is_primary = $data->isPrimary;
        $model->started_at = $data->startedAt;
        $model->ended_at = $data->endedAt;

        return $model;
    }
}


