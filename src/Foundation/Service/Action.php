<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Database\Eloquent\Model;

/**
 * @property Model   $model
 * @property Service $service
 */
abstract class Action implements ServiceAwareAction
{
    use HasPipelines;

    use CanUseDatabaseTransactions;


    /**
     * @return Service
     */
    public function getService() : Service
    {
        return $this->service;
    }


    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }


}
