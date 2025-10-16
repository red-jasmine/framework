<?php

namespace RedJasmine\FilamentCore\Panel;

use RedJasmine\Support\Facades\AES;

class Login extends \Filament\Auth\Pages\Login
{


    protected function getCredentialsFromFormData(array $data) : array
    {
        $data['email'] = AES::encryptString($data['email'] );

        return parent::getCredentialsFromFormData($data); 
    }

}