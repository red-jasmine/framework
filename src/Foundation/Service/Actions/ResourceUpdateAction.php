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
class ResourceUpdateAction extends AbstractResourceAction
{
    public static function name() : string
    {
        return 'update';
    }

    public int|string|null $key = null;

    public function execute(int|string $key, Data|array $data) : Model
    {
        $this->key = $key;

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
