<?php

namespace RedJasmine\Support\Application;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;

class HandleContext
{

    /**
     * 命令
     * @var Data
     */
    protected Data $command;

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


    /**
     * 领域模型
     * @var Model
     */
    protected Model $model;

    public function getModel() : Model
    {
        return $this->model;
    }


}