<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentChannel extends Model
{

    use SoftDeletes;

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channels';
    }


}