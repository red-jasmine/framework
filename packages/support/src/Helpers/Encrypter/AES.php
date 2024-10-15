<?php

namespace RedJasmine\Support\Helpers\Encrypter;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;


class AES
{
    /**
     * The encryption key.
     *
     * @var string
     */
    protected string $key;

    /**
     * The algorithm used for encryption.
     *
     * @var string
     */
    protected string $cipher = 'AES-256-ECB';


    public function __construct($key)
    {
        $key       = (string)$key;
        $this->key = $key;

    }


    public function encrypt($value, $serialize = true) : string
    {
        $value = openssl_encrypt($serialize ? serialize($value) : $value,
                                 strtolower($this->cipher),
                                 $this->key, OPENSSL_RAW_DATA);

        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }
        return base64_encode($value);
    }

    public function encryptString($value) : string
    {
        return $this->encrypt($value, false);
    }

    public function decrypt($value, $unserialize = true) : string
    {
        $value = base64_decode($value);

        $decrypted = openssl_decrypt(
            $value, strtolower($this->cipher),
            $this->key, OPENSSL_RAW_DATA
        );

        if ($decrypted === false) {
            throw new DecryptException('Could not decrypt the data.');
        }

        return $decrypted;
    }

    /**
     * Decrypt the given string without unserialization.
     *
     * @param string $payload
     *
     * @return string
     *
     * @throws DecryptException
     */
    public function decryptString($payload) : string
    {
        return $this->decrypt($payload, false);
    }

}
