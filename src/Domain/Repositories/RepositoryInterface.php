<?php

namespace RedJasmine\Support\Domain\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function find($id);

    public function store(Model $model);

    public function update(Model $model);

    public function delete(Model $model);
}
