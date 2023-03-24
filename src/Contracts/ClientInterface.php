<?php

namespace RedJasmine\Support\Contracts;

interface ClientInterface
{

    /**
     * 请求IP
     * @return string|null
     */
    public function getIp() : ?string;

    /**
     * UA
     * @return string|null
     */
    public function getUserAgent() : ?string;

    /**
     * SDK 信息
     * @return string|null
     */
    public function getSdkInfo() : ?string;

    /**
     * 客户端版本
     * @return string|null
     */
    public function getVersion() : ?string;


    /**
     * 其他信息
     * @return array
     */
    public function others() : array;

}
