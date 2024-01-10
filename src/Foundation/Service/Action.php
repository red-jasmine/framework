<?php

namespace RedJasmine\Support\Foundation\Service;

abstract class Action implements ServiceAwareAction
{
    use HasPipeline;

    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }


}
