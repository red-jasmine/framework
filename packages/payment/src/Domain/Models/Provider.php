<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class Provider extends Model
{

    use SoftDeletes;
    use HasOperator;


    public function getTable() : string
    {
        return 'payment_providers';
    }

}
