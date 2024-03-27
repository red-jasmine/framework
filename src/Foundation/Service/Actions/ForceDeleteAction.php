<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

class ForceDeleteAction extends ResourceAction
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
        return $this->forceDelete();
    }

    public function handle() : ?bool
    {
        return $this->model->forceDelete();
    }
}
