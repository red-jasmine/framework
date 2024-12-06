<?php

namespace RedJasmine\Payment\Domain\Models;


use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class Platform extends Model
{


    use HasOperator;


    protected $fillable = [
        'code',
        'name',
        'remarks',
        'icon'
    ];


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_platforms';
    }


}
