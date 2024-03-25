<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\HasValidatorCombiners;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @property ResourceService $service
 */
class ResourceCreateAction extends ResourceAction
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
