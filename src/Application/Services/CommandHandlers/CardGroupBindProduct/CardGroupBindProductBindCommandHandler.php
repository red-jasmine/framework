<?php

namespace RedJasmine\Card\Application\Services\CommandHandlers\CardGroupBindProduct;

use RedJasmine\Card\Application\Services\CardGroupBindProductCommandService;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductBindCommand;
use RedJasmine\Support\Application\CommandHandler;

/**
 * @method  CardGroupBindProductCommandService getService()
 */
class CardGroupBindProductBindCommandHandler extends CommandHandler
{


    public function handle(CardGroupBindProductBindCommand $command)
    {

        $service = $this->getService();


        $model = $service->getRepository()->findByProduct($command->owner, $command->productType, $command->productId, $command->skuId);


        if ($model) {
            $model->group_id = $command->groupId;
            $service->getRepository()->update($model);
        } else {
            return $this->getService()->create($command);
        }
        return $model;

    }
}
