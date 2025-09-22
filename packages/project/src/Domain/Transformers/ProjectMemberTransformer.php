<?php

namespace RedJasmine\Project\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Project\Domain\Data\ProjectMemberData;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class ProjectMemberTransformer implements TransformerInterface
{
    public function transform($data, $model): Model
    {
        if (!$model instanceof ProjectMember) {
            throw new \InvalidArgumentException('Model must be an instance of ProjectMember');
        }

        if ($data instanceof ProjectMemberData) {
            $model->fill([
                'project_id' => $data->projectId,
                'member_type' => $data->member->getType(),
                'member_id' => $data->member->getID(),
                'status' => $data->status,
                'joined_at' => $data->joinedAt,
                'left_at' => $data->leftAt,
                'invited_by_type' => $data->invitedBy?->getType(),
                'invited_by_id' => $data->invitedBy?->getID(),
                'permissions' => $data->permissions,
            ]);
        } elseif (is_array($data)) {
            $model->fill($data);
        }

        return $model;
    }
}
