<?php

namespace RedJasmine\Support\Traits\Services;

use RedJasmine\Support\Contracts\ClientInterface;

/**
 * 客户端
 */
trait WithClientService
{

    protected ?ClientInterface $client = null;

    public function getClient() : ?ClientInterface
    {
        return $this->client;
    }

    public function setClient(?ClientInterface $client = null) : static
    {
        $this->client = $client;
        return $this;
    }


}
