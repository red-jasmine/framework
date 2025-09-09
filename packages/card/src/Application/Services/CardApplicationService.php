<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Application\Services\Pipelines\CardGroupPipeline;
use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 卡密应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 */
class CardApplicationService extends ApplicationService
{
    protected static string $modelClass = Card::class;

    public function __construct(
        public CardRepositoryInterface $repository,
    ) {
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
            'delete' => [],
        ];
    }
}
