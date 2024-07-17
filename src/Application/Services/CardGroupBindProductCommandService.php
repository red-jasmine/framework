<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Application\Services\Pipelines\CardGroupPipeline;
use RedJasmine\Card\Domain\Models\CardGroupBindProduct;
use RedJasmine\Card\Domain\Repositories\CardGroupBindProductRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class CardGroupBindProductCommandService extends ApplicationCommandService
{


    protected static string $modelClass = CardGroupBindProduct::class;


    public function __construct(
        protected CardGroupBindProductRepositoryInterface $repository,
    )
    {

        parent::__construct();
    }


    protected function pipelines() : array
    {
        return  [
            'create'=>[
                CardGroupPipeline::class
            ],
            'update'=>[
                CardGroupPipeline::class
            ],
        ];
    }

}
