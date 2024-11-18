<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class PaymentIsv extends Model
{

    use SoftDeletes;
    use HasOperator;


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix') . 'payment_isv';
    }

}
