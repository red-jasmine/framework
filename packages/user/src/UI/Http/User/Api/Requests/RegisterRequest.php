<?php

namespace RedJasmine\User\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules() : array
    {
        return [

        ];
    }

    public function authorize() : bool
    {
        return false;
    }
}
