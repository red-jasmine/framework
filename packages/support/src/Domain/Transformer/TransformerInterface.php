<?php

namespace RedJasmine\Support\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;

interface TransformerInterface
{
    /**
     * @param  Data  $data
     * @param  Model  $model
     *
     * @return Model
     */
    public function transform( $data, $model) : mixed;
}
