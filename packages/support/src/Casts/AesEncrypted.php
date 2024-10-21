<?php

namespace RedJasmine\Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Facades\AES;
use Throwable;

class AesEncrypted implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes) : ?string
    {
        if (filled($value)) {
            try {
                return AES::decryptString($value);
            } catch (Throwable $throwable) {
                report($throwable);
            }

        }
        return null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (filled($value)) {
            try {
                return AES::encryptString($value);
            }catch (Throwable $throwable){
                report($throwable);
            }

        }
        return null;
    }


}
