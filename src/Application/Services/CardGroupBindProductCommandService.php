<?php

namespace RedJasmine\Card\Application\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Card\Application\Services\Pipelines\CardGroupPipeline;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductCreateCommand;
use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Card\Exceptions\CardException;
use RedJasmine\Support\Application\ApplicationCommandService;


/**
 * @method CardGroupBindProductRepositoryInterface  getRepository()
 */
class CardGroupBindProductCommandService extends ApplicationCommandService
{


    protected static string $modelClass = CardGroupBindProduct::class;


    public function __construct(
        public CardGroupBindProductRepositoryInterface $repository,
    )
    {

        parent::__construct();
    }

    /**
     * @param CardGroupBindProductCreateCommand $command
     *
     * @return Model
     * @throws \Exception
     */
    public function newModel($command = null) : Model
    {

        // 验证商品是否已经绑定

        if ($this->repository->findByProduct($command->owner, $command->productType, $command->productId, $command->skuId)) {
            throw new CardException('商品已绑定分组');
        }


        return parent::newModel($command); // TODO: Change the autogenerated stub
    }


    protected function pipelines() : array
    {
        return [
            'create' => [
                CardGroupPipeline::class
            ],
            'update' => [
                CardGroupPipeline::class
            ],
        ];
    }

}
