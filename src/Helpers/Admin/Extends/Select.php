<?php

namespace RedJasmine\Support\Helpers\Admin\Extends;

use Dcat\Admin\Admin;

class Select extends \Dcat\Admin\Grid\Displayers\Select
{
    public function display($options = [], $refresh = false)
    {
        if ($options instanceof \Closure) {
            $options = $options->call($this, $this->row);
        }

        return Admin::view('admin::grid.displayer.select', [
            'column'  => $this->column->getName(),
            'value'   => $this->value->value??$this->value,
            'url'     => $this->url(),
            'options' => $options,
            'refresh' => $refresh,
        ]);
    }

}
