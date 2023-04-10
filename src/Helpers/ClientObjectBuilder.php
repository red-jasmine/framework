<?php

namespace RedJasmine\Support\Helpers;

use Illuminate\Http\Request;
use RedJasmine\Support\Contracts\ClientInterface;

class ClientObjectBuilder implements ClientInterface
{
    public function __construct(public Request $request)
    {
    }

    public function getIp() : ?string
    {
        return $this->request->getClientIp();
    }

    public function getUserAgent() : ?string
    {
        return $this->request->userAgent();
    }

    public function getSdk() : ?string
    {
        return $this->request->header('x-sdk', 'anr');
    }

    public function getVersion() : ?string
    {
        return $this->request->header('x-version', 'v1.0.0');
    }

    /**
     * @return string|null
     */
    public function getReferer() : ?string
    {
        return $this->request->header('referer', '');
    }

    /**
     * @return string|null
     */
    public function getUrl() : ?string
    {
        return $this->request->getUri();
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
