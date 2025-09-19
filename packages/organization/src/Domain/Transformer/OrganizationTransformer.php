<?php

namespace RedJasmine\Organization\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Organization\Domain\Data\OrganizationData;
use RedJasmine\Organization\Domain\Models\Organization;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class OrganizationTransformer implements TransformerInterface
{
    /** @param OrganizationData $data */
    public function transform($data, $model) : Model
    {
        if (!$model instanceof Organization) {
            $model = new Organization();
        }

        $model->parent_id = $data->parentId;
        $model->name = $data->name;
        $model->short_name = $data->shortName;
        $model->code = $data->code;
        $model->sort = $data->sort;
        $model->status = $data->status;

        return $model;
    }
}


