<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

class ResourceRestoreAction extends ResourceAction
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
        return $this->restore();
    }

    public function handle() : ?bool
    {
        return $this->model->restore();
    }

}
