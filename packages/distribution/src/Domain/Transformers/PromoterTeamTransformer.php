<?php

namespace RedJasmine\Distribution\Domain\Transformers;

use RedJasmine\Distribution\Domain\Data\PromoterTeamData;
use RedJasmine\Distribution\Domain\Models\PromoterTeam;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Transformer\CategoryTransformer;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class PromoterTeamTransformer extends CategoryTransformer implements TransformerInterface
{
    /**
     * @param  PromoterTeamData  $data
     * @param  BaseCategoryModel  $model
     *
     * @return BaseCategoryModel
     */
    public function transform($data, $model) : BaseCategoryModel
    {
        /**
         * @var PromoterTeam $model
         * @var PromoterTeamData $model
         */
        $model = parent::transform($data, $model);

        $model->leader_id = $data->leaderId;

        return $model;
    }


}