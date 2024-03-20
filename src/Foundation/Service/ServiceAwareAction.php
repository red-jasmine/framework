<?php

namespace RedJasmine\Support\Foundation\Service;

interface ServiceAwareAction
{
    public function setService($service) : static;

    public function getService();
}
