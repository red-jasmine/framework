<?php

namespace RedJasmine\Support\Foundation\Service;

interface ServiceAwareAction
{
    public function setService(Service $service) : static;
}
