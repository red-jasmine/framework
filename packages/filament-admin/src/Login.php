<?php

namespace RedJasmine\FilamentAdmin;

use RedJasmine\Support\Facades\AES;

class Login extends \Filament\Pages\Auth\Login
{


    protected function getCredentialsFromFormData(array $data) : array
    {
        $data['email'] = AES::encryptString($data['email'] );

        return parent::getCredentialsFromFormData($data); 
    }

}