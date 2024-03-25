<?php

namespace RedJasmine\Support\Foundation\Validators;

use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;

interface ActionAwareValidatorCombiner
{


    /**
     * @param ResourceAction $action
     */
    public function setAction(ResourceAction $action) : void;
}
