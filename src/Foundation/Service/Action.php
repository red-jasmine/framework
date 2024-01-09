<?php

namespace RedJasmine\Support\Foundation\Service;

class Action implements ServiceAwareAction
{
    public function setService(Service $service) : static
    {
        $this->service = $service;
        return $this;
    }


}
