<?php

namespace RedJasmine\Support\Traits\Models;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\WithData;

trait WithDTO
{


    protected ?Data $_DTO = null;

    public function getDTO() : ?Data
    {
        return $this->_DTO;
    }

    public function setDTO(Data $data) : static
    {
        $this->_DTO = $data;
        return $this;
    }


}
