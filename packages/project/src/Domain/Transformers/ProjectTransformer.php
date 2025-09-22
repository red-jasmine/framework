<?php

namespace RedJasmine\Project\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Project\Domain\Data\ProjectData;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ProjectTransformer implements TransformerInterface
{
    public function transform($data, $model): Model
    {
        if (!$model instanceof Project) {
            throw new \InvalidArgumentException('Model must be an instance of Project');
        }

        if ($data instanceof ProjectData) {
            $model->fill([
                'owner_type' => $data->owner->getType(),
                'owner_id' => $data->owner->getID(),
                'parent_id' => $data->parentId,
                'name' => $data->name,
                'short_name' => $data->shortName,
                'description' => $data->description,
                'code' => $data->code,
                'project_type' => $data->projectType,
                'status' => $data->status,
                'sort' => $data->sort,
                'config' => $data->config,
            ]);
        } elseif (is_array($data)) {
            $model->fill($data);
        }

        return $model;
    }
}
