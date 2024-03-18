<?php

namespace RedJasmine\Support\Foundation\Service;

abstract class Action implements ServiceAwareAction
{


    public ?string $callName = null;
    use HasPipeline;
    use CanUseDatabaseTransactions;

    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }


}
