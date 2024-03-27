<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Foundation\Service\ResourceService;

/**
 * @property ResourceService $service
 */
class CreateAction extends ResourceAction
{

    /**
     * @param Data|array $data
     *
     * @return Model
     */
    public function execute($data) : Model
    {
        $this->data = $data;
        return $this->store();
    }


    /**
     * @return Model
     * @throws Exception
     */
    public function handle() : Model
    {

        $this->model->save();
        return $this->model;
    }

}
