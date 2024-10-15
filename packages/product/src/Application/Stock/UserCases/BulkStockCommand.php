<?php

namespace RedJasmine\Product\Application\Stock\UserCases;


use Illuminate\Support\Collection;
use RedJasmine\Support\Data\Data;


class BulkStockCommand extends Data
{

    /**
     * @var Collection<StockCommand>
     */
    public Collection $skus;


    public static function prepareForPipeline(array $properties) : array
    {

        foreach ($properties['skus'] as $index => $sku) {

            if (blank($sku['action_stock'] ?? null)) {
                unset($properties['skus'][$index]);
            }
        }
        return $properties;
    }

}
