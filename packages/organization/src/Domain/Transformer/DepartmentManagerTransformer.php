<?php

namespace RedJasmine\Organization\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Data\DepartmentManagerData;
use RedJasmine\Organization\Domain\Models\DepartmentManager;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class DepartmentManagerTransformer implements TransformerInterface
{
    /** @param DepartmentManagerData $data */
    public function transform($data, $model) : Model
    {
        if (!$model instanceof DepartmentManager) {
            $model = new DepartmentManager();
        }

        $model->department_id = $data->departmentId;
        $model->member_id = $data->memberId;
        $model->is_primary = $data->isPrimary;
        $model->started_at = $data->startedAt;
        $model->ended_at = $data->endedAt;

        return $model;
    }
}


