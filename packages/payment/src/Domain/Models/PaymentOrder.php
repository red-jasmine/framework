<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class PaymentOrder extends Model
{

    use HasOperator;
    use SoftDeletes;
}
