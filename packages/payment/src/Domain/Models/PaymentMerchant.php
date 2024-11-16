<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

class PaymentMerchant extends Model
{

    use HasOwner;


    use SoftDeletes;
}
