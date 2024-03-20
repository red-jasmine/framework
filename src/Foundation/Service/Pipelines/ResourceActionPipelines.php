<?php

namespace RedJasmine\Support\Foundation\Service\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Foundation\Service\Actions\ResourceAction;

class ResourceActionPipelines
{

    public function init(ResourceAction $action, Closure $next) : Data
    {
        return $next($action);
    }

    public function validate(ResourceAction $action, Closure $next) : array
    {
        return $next($action);
    }

    public function fill(ResourceAction $action, Closure $next) : ?Model
    {
        return $next($action);
    }

    public function handle(ResourceAction $action, Closure $next) : mixed
    {
        return $next($action);
    }

    public function after(ResourceAction $action, Closure $next) : mixed
    {
        return $next($action);
    }

}
