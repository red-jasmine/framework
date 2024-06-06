<?php

namespace RedJasmine\Support\Foundation\Service;

trait AwareServiceAble
{
    protected Service $service;

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
