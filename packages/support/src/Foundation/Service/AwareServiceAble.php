<?php

namespace RedJasmine\Support\Foundation\Service;

/**
 * 能感知服务
 */
trait AwareServiceAble
{


    public function getService() : Service
    {
        return $this->service;
    }

    public function setService(Service $service) : static
    {
        $this->service = $service;
        return $this;
    }


}
