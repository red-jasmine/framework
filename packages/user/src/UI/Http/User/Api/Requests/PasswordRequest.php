<?php

namespace RedJasmine\User\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'password' => 'required|string|min:6|max:20'
        ];
    }

    public function authorize() : bool
    {
        return true;
    }
}
