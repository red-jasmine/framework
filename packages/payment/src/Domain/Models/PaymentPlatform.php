<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class PaymentPlatform extends Model
{
    use SoftDeletes;

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
