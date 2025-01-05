<?php

namespace RedJasmine\Order\Domain\Models;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function getConnectionName()
    {
        return config('red-jasmine-order.tables.connection', null);
    }

}
