<?php

namespace RedJasmine\Support\Domain\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface ClientInterface extends Arrayable
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
    public function getSdk() : ?string;

    /**
     * 客户端版本
     * @return string|null
     */
    public function getVersion() : ?string;

    /**
     * 来源
     * @return string|null
     */
    public function getReferer() : ?string;

    /**
     * page or url
     * @return string|null
     */
    public function getUrl() : ?string;

    /**
     * 其他信息
     * @return array
     */
    public function others() : array;

}
