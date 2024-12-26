<?php

namespace RedJasmine\Support\Helpers\Signer;

use Exception;
use JsonException;
use RuntimeException;


class Signer
{
    public const string ENCODE_POLICY_QUERY = 'QUERY';
    public const string ENCODE_POLICY_JSON  = 'JSON';

    public const int KEY_TYPE_PUBLIC  = 1;
    public const int KEY_TYPE_PRIVATE = 2;

    protected array $ignores = [ 'sign' ];

    protected bool $sort = true;

    protected string $encodePolicy = self::ENCODE_POLICY_QUERY;

    /**
     * @var array
     */
    private array $params;


    public function __construct(array $params = [])
    {
        $this->params = $params;
    }


    /**
     * @param $key
     *
     * @return string
     * @throws JsonException
     */
    public function signWithMD5($key) : string
    {
        $content = $this->getContentToSign();

        return md5($content . $key);
    }


    /**
     * @return false|string|null
     * @throws JsonException
     */
    public function getContentToSign() : false|string|null
    {
        $params = $this->getParamsToSign();

        if ($this->encodePolicy === self::ENCODE_POLICY_QUERY) {
            return urldecode(http_build_query($params));
        } elseif ($this->encodePolicy === self::ENCODE_POLICY_JSON) {
            return json_encode($params, JSON_THROW_ON_ERROR);
        } else {
            return null;
        }
    }


    /**
     * @return mixed
     */
    public function getParamsToSign() : mixed
    {
        $params = $this->params;

        $this->unsetKeys($params);

        $params = $this->filter($params);

        if ($this->sort) {
            $this->sort($params);
        }

        return $params;
    }


    /**
     * @param $params
     */
    protected function unsetKeys(&$params) : void
    {
        foreach ($this->getIgnores() as $key) {
            unset($params[$key]);
        }
    }


    /**
     * @return array
     */
    public function getIgnores() : array
    {
        return $this->ignores;
    }


    /**
     * @param array $ignores
     *
     * @return $this
     */
    public function setIgnores(array $ignores) : static
    {
        $this->ignores = $ignores;

        return $this;
    }


    private function filter($params) : array
    {
        return array_filter($params, 'strlen');
    }


    /**
     * @param $params
     */
    protected function sort(&$params) : void
    {
        ksort($params);
    }


    /**
     * @param string $privateKey
     * @param int $alg
     *
     * @return string
     * @throws Exception
     */
    public function signWithRSA(string $privateKey, int $alg = OPENSSL_ALGO_SHA1) : string
    {
        $content = $this->getContentToSign();

        return $this->signContentWithRSA($content, $privateKey, $alg);
    }


    /**
     * @param string $content
     * @param string $privateKey
     * @param int $alg
     *
     * @return string
     * @throws Exception
     */
    public function signContentWithRSA(string $content, string $privateKey, int $alg = OPENSSL_ALGO_SHA1) : string
    {
        $privateKey = $this->prefix($privateKey);
        $privateKey = $this->format($privateKey, self::KEY_TYPE_PRIVATE);
        $res        = openssl_pkey_get_private($privateKey);

        $sign = null;

        try {
            openssl_sign($content, $sign, $res, $alg);
        } catch (Exception $e) {
            if ($e->getCode() === 2) {
                $message = $e->getMessage();
                $message .= "\n应用私钥格式有误";
                throw new RuntimeException($message, $e->getCode(), $e);
            }
        }


        return base64_encode($sign);
    }


    /**
     * Prefix the key path with 'file://'
     *
     * @param $key
     *
     * @return string
     */
    private function prefix($key)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN' && is_file($key) && substr($key, 0, 7) != 'file://') {
            $key = 'file://' . $key;
        }

        return $key;
    }


    /**
     * Convert key to standard format
     *
     * @param $key
     * @param $type
     *
     * @return string
     */
    public function format($key, $type)
    {
        if (is_file($key)) {
            $key = file_get_contents($key);
        }


        if (is_string($key) && strpos($key, '-----') === false) {
            $key = $this->convertKey($key, $type);
        }

        return $key;
    }


    /**
     * Convert one line key to standard format
     *
     * @param string $key
     * @param int $type
     *
     * @return string
     */
    public function convertKey($key, $type)
    {
        $lines = [];

        if ($type === self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----BEGIN PUBLIC KEY-----';
        } else {
            $lines[] = '-----BEGIN RSA PRIVATE KEY-----';
        }

        for ($i = 0, $iMax = strlen($key); $i < $iMax; $i += 64) {
            $lines[] = trim(substr($key, $i, 64));
        }

        if ($type === self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----END PUBLIC KEY-----';
        } else {
            $lines[] = '-----END RSA PRIVATE KEY-----';
        }

        return implode("\n", $lines);
    }


    public function verifyWithMD5($content, $sign, $key) : bool
    {
        return md5($content . $key) === $sign;
    }


    /**
     * @param string $content
     * @param string $sign
     * @param string $publicKey
     * @param int $alg
     *
     * @return bool
     * @throws Exception
     */
    public function verifyWithRSA(string $content, string $sign, string $publicKey, int $alg = OPENSSL_ALGO_SHA1) : bool
    {
        $publicKey = $this->prefix($publicKey);

        $publicKey = $this->format($publicKey, self::KEY_TYPE_PUBLIC);

        $res = openssl_pkey_get_public($publicKey);

        if (!$res) {
            $message = "The public key is invalid";
            $message .= "\n公钥格式有误;";
            throw new RuntimeException($message);
        }

        return (bool)openssl_verify($content, base64_decode($sign), $res, $alg);

    }


    /**
     * @param boolean $sort
     *
     * @return Signer
     */
    public function setSort(bool $sort) : Signer
    {
        $this->sort = $sort;

        return $this;
    }


    /**
     * @param string $encodePolicy
     *
     * @return Signer
     */
    public function setEncodePolicy(string $encodePolicy) : Signer
    {
        $this->encodePolicy = $encodePolicy;

        return $this;
    }


    /**
     * @param int $bits
     * @param int $type
     * @return array{public:string,private:string}
     */
    public function generateKeys(int $bits = 2048, int $type = OPENSSL_KEYTYPE_RSA) : array
    {
        // 生成密钥对
        $config = array (
            'digest_alg'       => 'sha256',
            "private_key_bits" => $bits, // 密钥长度
            "private_key_type" => $type,
        );

        // 创建密钥对
        $res = openssl_pkey_new($config);

        // 提取私钥
        openssl_pkey_export($res, $privateKey);

        // 提取公钥
        $publicKeyDetails = openssl_pkey_get_details($res);
        $publicKey        = $publicKeyDetails["key"];

        // 去除私钥的头部和尾部
        $privateKey = $this->removeKeyHeaders($privateKey);

        // 去除公钥的头部和尾部
        $publicKey = $this->removeKeyHeaders($publicKey);

        return [
            'private' => $privateKey,
            'public'  => $publicKey,
        ];
    }

    /**
     * 去除密钥的头部和尾部
     *
     * @param string $key
     * @return string
     */
    private function removeKeyHeaders(string $key) : string
    {
        $key = str_replace([ '-----BEGIN RSA PRIVATE KEY-----',
                             '-----END RSA PRIVATE KEY-----',
                             '-----BEGIN PRIVATE KEY-----',
                             '-----END PRIVATE KEY-----',
                             '-----BEGIN PUBLIC KEY-----',
                             '-----END PUBLIC KEY-----' ], '', $key);
        $key = str_replace([ "\n", "\r" ], '', $key);
        return $key;
    }
}
