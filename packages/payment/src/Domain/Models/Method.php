<?php

namespace RedJasmine\Payment\Domain\Models;


use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class Method extends Model
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
        return  'payment_methods';
    }


}
