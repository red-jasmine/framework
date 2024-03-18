<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @property Service $service
 */
class ResourceCreateAction extends AbstractResourceAction
{

    /**
     * @param Data|array $data
     *
     * @return Model
     */
    public function execute(Data|array $data) : Model
    {
        $this->data = $this->conversionData($data);
        return $this->save();
    }


    /**
     * @return Model
     * @throws Exception
     */
    public function handle() : Model
    {
        if ($this->model->incrementing === false) {
            $this->model->{$this->model->getKeyName()} = $this->service::buildID();
        }
        $this->model->save();
        return $this->model;
    }

}
