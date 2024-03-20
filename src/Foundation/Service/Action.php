<?php

namespace RedJasmine\Support\Foundation\Service;

/**
 * @property Service $service
 */
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

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }


}
