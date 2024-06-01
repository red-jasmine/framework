<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\Service\Service;

trait WithService
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
