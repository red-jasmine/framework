<?php

namespace RedJasmine\Order\Models;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Facades\AES;

class AesEncrypted implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes) : ?string
    {
        if (filled($value)) {
            return AES::decryptString($value);
        }
        return null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (filled($value)) {
            return AES::encryptString($value);
        }
        return null;
    }


}
