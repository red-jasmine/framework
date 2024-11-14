<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentOrder extends Model
{


    use SoftDeletes;
}
