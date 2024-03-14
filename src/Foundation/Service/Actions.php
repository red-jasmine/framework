<?php

namespace RedJasmine\Support\Foundation\Service;

abstract class Actions implements ServiceAwareAction
{
    use HasPipelines;
    use CanUseDatabaseTransactions;

    public $service;

    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }


}
