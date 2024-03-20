<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Database\Eloquent\Model;

/**
 * @property Model   $model
 * @property Service $service
 */
abstract class Actions implements ServiceAwareAction
{
    use HasPipelines;

    use CanUseDatabaseTransactions;


    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }


    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }


}
