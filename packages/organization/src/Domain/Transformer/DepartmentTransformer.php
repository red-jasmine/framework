<?php

namespace RedJasmine\Organization\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Data\DepartmentData;
use RedJasmine\Organization\Domain\Models\Department;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class DepartmentTransformer implements TransformerInterface
{
    /** @param DepartmentData $data */
    public function transform($data, $model) : Model
    {
        if (!$model instanceof Department) {
            $model = new Department();
        }

        $model->org_id = $data->orgId;
        $model->parent_id = $data->parentId;
        $model->name = $data->name;
        $model->short_name = $data->shortName;
        $model->code = $data->code;
        $model->sort = $data->sort;
        $model->status = $data->status;

        return $model;
    }
}


