<?php

namespace RedJasmine\Support\Helpers;

use Illuminate\Http\Request;
use RedJasmine\Support\Contracts\ClientInterface;

class ClientObjectBuilder implements ClientInterface
{
    public function __construct(Request $request)
    {

        $this->ip        = $request->getClientIp();
        $this->userAgent = $request->userAgent();
        $this->sdk       = $request->header('x-sdk', 'anr');
        $this->version   = $request->header('x-version', 'v1.0.0');
        $this->referer   = $request->header('referer', '');
        $this->url       = $request->getUri();
    }


    public ?string $ip;
    public ?string $userAgent;
    public ?string $sdk;
    public ?string $version;
    public ?string $referer;
    public ?string $url;

    /**
     * @return string|null
     */
    public function getIp() : ?string
    {
        return $this->ip;
    }

    /**
     * @return string|null
     */
    public function getUserAgent() : ?string
    {
        return $this->userAgent;
    }

    /**
     * @return string|null
     */
    public function getSdk() : ?string
    {
        return $this->sdk;
    }

    /**
     * @return string|null
     */
    public function getVersion() : ?string
    {
        return $this->version;
    }

    /**
     * @return string|null
     */
    public function getReferer() : ?string
    {
        return $this->referer;
    }

    /**
     * @return string|null
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }


    public function others() : array
    {
        return [];
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'ip'        => $this->getIp(),
            'userAgent' => $this->getUserAgent(),
            'sdk'       => $this->getSdk(),
            'version'   => $this->getVersion(),
            'url'       => $this->getUrl(),
            'referer'   => $this->getReferer(),
            'others'    => $this->others(),
        ];

    }


}
