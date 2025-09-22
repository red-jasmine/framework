<?php

namespace RedJasmine\Project\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Project\Domain\Data\ProjectRoleData;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ProjectRoleTransformer implements TransformerInterface
{
    public function transform($data, $model): Model
    {
        if (!$model instanceof ProjectRole) {
            throw new \InvalidArgumentException('Model must be an instance of ProjectRole');
        }

        if ($data instanceof ProjectRoleData) {
            $model->fill([
                'project_id' => $data->projectId,
                'name' => $data->name,
                'code' => $data->code,
                'description' => $data->description,
                'is_system' => $data->isSystem,
                'permissions' => $data->permissions,
                'sort' => $data->sort,
                'status' => $data->status,
            ]);
        } elseif (is_array($data)) {
            $model->fill($data);
        }

        return $model;
    }
}
