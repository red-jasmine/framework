<?php

namespace RedJasmine\Card\Application\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Card\Application\Services\CommandHandlers\CardGroupBindProduct\CardGroupBindProductBindCommandHandler;
use RedJasmine\Card\Application\Services\Pipelines\CardGroupPipeline;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductBindCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductCreateCommand;
use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Card\Exceptions\CardException;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 卡密分组绑定商品应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 *
 * @method bind(CardGroupBindProductBindCommand $command)
 */
class CardGroupBindProductApplicationService extends ApplicationService
{
    protected static string $modelClass = CardGroupBindProduct::class;

    public function __construct(
        public CardGroupBindProductRepositoryInterface $repository,
    ) {
    }

    protected static $macros = [
        'bind' => CardGroupBindProductBindCommandHandler::class,
    ];

    /**
     * @param  CardGroupBindProductCreateCommand  $command
     *
     * @return Model
     * @throws Exception
     */
    public function newModel($command = null) : Model
    {
        // 验证商品是否已经绑定
        if ($this->repository->findByProduct($command->owner, $command->productType, $command->productId, $command->skuId)) {
            throw new CardException('商品已绑定分组');
        }

        return parent::newModel($command);
    }

    protected function hooks() : array
    {
        return [
            'create' => [
                CardGroupPipeline::class
            ],
            'update' => [
                CardGroupPipeline::class
            ],
            'bind'   => [
                CardGroupPipeline::class
            ],
        ];
    }
}
