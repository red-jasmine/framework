<?php

namespace RedJasmine\Support\Application;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;

/**
 * @deprecated
 */
class HandleContext
{

    /**
     * 命令
     * @var Data
     */
    protected Data $command;


    /**
     * 领域模型
     * @var Model
     */
    public Model $model;

    public function getCommand() : Data
    {
        return $this->command;
    }

    public function setCommand(Data $command) : HandleContext
    {
        $this->command = $command;
        return $this;
    }

    public function setModel(Model $model) : HandleContext
    {

        $this->model = $model;
        return $this;
    }



    public function getModel() : Model
    {
        return $this->model;
    }


}