<?php

namespace RedJasmine\Support\Application\Commands;

use Illuminate\Database\Eloquent\Model;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Foundation\Context\Context;

/**
 * @property-read Data $command
 * @property-read ?Model $model
 *
 */
class CommandContext extends Context
{
    public function getCommand() : Data
    {
        return $this->get('command');
    }

    public function setCommand(Data $command) : CommandContext
    {
        $this->set('command', $command);
        return $this;
    }

    public function getModel() : ?Model
    {
        return $this->get('model');
    }

    public function setModel(?Model $model) : CommandContext
    {
        $this->set('model', $model);
        return $this;
    }


}