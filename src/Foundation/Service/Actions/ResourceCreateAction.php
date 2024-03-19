<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\ResourceService;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * @property ResourceService $service
 */
class ResourceCreateAction extends AbstractResourceAction
{
    public static function name() : string
    {
        return 'create';
    }


    /**
     * @param Data|array $data
     *
     * @return Model
     */
    public function execute(Data|array $data) : Model
    {
        $this->data = $data;
        return $this->save();
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
