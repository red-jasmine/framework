<?php

namespace RedJasmine\Product\Application\Stock\Services\Commands;


use Illuminate\Support\Collection;
use RedJasmine\Support\Foundation\Data\Data;


class BulkStockCommand extends Data
{

    /**
     * @var Collection<StockCommand>
     */
    public Collection $variants;


    public static function prepareForPipeline(array $properties) : array
    {

        foreach ($properties['variants'] as $index => $sku) {

            if (blank($sku['action_stock'] ?? null)) {
                unset($properties['variants'][$index]);
            }
        }
        return $properties;
    }

}
