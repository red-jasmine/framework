<?php

namespace RedJasmine\Support\Foundation\Service;

trait AwareArgumentsAble
{

    protected array $arguments = [];

    public function getArguments() : array
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments) : static
    {
        $this->arguments = $arguments;
        return $this;
    }


}
