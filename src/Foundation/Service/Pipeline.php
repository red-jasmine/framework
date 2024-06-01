<?php

namespace RedJasmine\Support\Foundation\Service;


use Closure;
use Illuminate\Pipeline\Pipeline as IlluminatePipeline;


class Pipeline extends IlluminatePipeline
{

    public function call($method, ?Closure $destination = null)
    {
        try {
            if ($destination === null) {
                $destination = function () {
                };
            }
            return $this->via($method)->then($destination);
        } finally {
            $this->method = 'handle';
        }
    }

    protected array $pipesObjects = [];


    protected function carry() : Closure
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                try {
                    if (is_callable($pipe)) {
                        // If the pipe is a callable, then we will call it directly, but otherwise we
                        // will resolve the pipes out of the dependency container and call it with
                        // the appropriate method and arguments, returning the results back out.
                        return $pipe($passable, $stack);
                    } elseif (!is_object($pipe)) {
                        [ $name, $parameters ] = $this->parsePipeString($pipe);

                        // If the pipe is a string we will parse the string and resolve the class out
                        // of the dependency injection container. We can then build a callable and
                        // execute the pipe function giving in the parameters that are required.

                        $this->pipesObjects[$name] = $pipe = $this->pipesObjects[$name] ?? $this->getContainer()->make($name);

                        $parameters = array_merge([ $passable, $stack ], $parameters);
                    } else {
                        // If the pipe is already an object we'll just make a callable and pass it to
                        // the pipe as-is. There is no need to do any extra parsing and formatting
                        // since the object we're given was already a fully instantiated object.
                        $parameters = [ $passable, $stack ];
                    }
                    $carry = method_exists($pipe, $this->method)
                        ? $pipe->{$this->method}(...$parameters)
                        : $stack($passable);

                    return $this->handleCarry($carry);
                } catch (\Throwable $e) {

                    return $this->handleException($passable, $e);
                }
            };
        };
    }
}
