<?php

namespace RedJasmine\Support\Domain\Transformer;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;

interface TransformerInterface
{
    public function transform(Data $data, ?Model $model = null) : ?Model;
}
