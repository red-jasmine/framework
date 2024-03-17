<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use RedJasmine\Support\Foundation\Service\Service;

/**
 * @property Service $service
 */
class AbstractDeleteAction extends AbstractResourceAction
{


    public int|string|null $key = null;

    /**
     * @param int|string $key
     *
     * @return bool|null
     */
    public function execute(int|string $key) : bool|null
    {
        $this->key = $key;
        return $this->delete();
    }

    public function handle() : void
    {
        $this->model->delete();
    }


}
