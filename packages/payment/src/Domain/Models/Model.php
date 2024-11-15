<?php

namespace RedJasmine\Payment\Domain\Models;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{

    public function getConnectionName()
    {
        return config('red-jasmine-payment.tables.connection', null);
    }

}
