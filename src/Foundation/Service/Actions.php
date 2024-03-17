<?php

namespace RedJasmine\Support\Foundation\Service;


use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $model
 * @property Service $service
 */
abstract class Actions implements ServiceAwareAction
{
    use HasPipelines;

    use CanUseDatabaseTransactions;

    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }


}
