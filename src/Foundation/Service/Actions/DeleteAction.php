<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use RedJasmine\Support\Foundation\Service\ResourceService;

/**
 * @property ResourceService $service
 */
class DeleteAction extends ResourceAction
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

    public function handle() : ?bool
    {
        return $this->model->delete();
    }


}
