<?php

namespace RedJasmine\Support\Foundation\Service\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\Actions\AbstractResourceAction;

class ResourceActionPipelines
{

    public function init(AbstractResourceAction $action, Closure $next) : Data
    {
        return $next($action);
    }

    public function validate(AbstractResourceAction $action, Closure $next) : array
    {
        return $next($action);
    }

    public function fill(AbstractResourceAction $action, Closure $next) : ?Model
    {
        return $next($action);
    }

    public function handle(AbstractResourceAction $action, Closure $next) : mixed
    {
        return $next($action);
    }

    public function after(AbstractResourceAction $action, Closure $next) : mixed
    {
        return $next($action);
    }

}
