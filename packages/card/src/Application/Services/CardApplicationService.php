<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Application\Services\Pipelines\CardGroupPipeline;
use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class CardApplicationService extends ApplicationService
{


    protected static string $modelClass = Card::class;


    public function __construct(
        public CardRepositoryInterface $repository,
        public CardReadRepositoryInterface $readRepository,
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
