<?php

namespace RedJasmine\Product\Application\Series\Services;

use RedJasmine\Product\Application\Series\Services\CommandHandlers\ProductSeriesCreateCommandHandler;
use RedJasmine\Product\Application\Series\Services\CommandHandlers\ProductSeriesDeleteCommandHandler;
use RedJasmine\Product\Application\Series\Services\CommandHandlers\ProductSeriesUpdateCommandHandler;
use RedJasmine\Product\Application\Series\Services\Pipelines\SeriesProductPipeline;
use RedJasmine\Product\Domain\Series\Models\ProductSeries;
use RedJasmine\Product\Domain\Series\Repositories\ProductSeriesRepositoryInterface;
use RedJasmine\Support\Application\ApplicationCommandService;

class ProductSeriesCommandService extends ApplicationCommandService
{

    protected static string $modelClass = ProductSeries::class;

    protected static $macros = [
        'create' => ProductSeriesCreateCommandHandler::class,
        'update' => ProductSeriesUpdateCommandHandler::class,
        'delete' => ProductSeriesDeleteCommandHandler::class
    ];

    public function __construct(
        protected ProductSeriesRepositoryInterface $repository
    )
    {
        parent::__construct();
    }


    protected function pipelines() : array
    {
        return [
            'create' => [
                SeriesProductPipeline::class,
            ],
            'update' => [
                SeriesProductPipeline::class,
            ]
        ];
    }

}
