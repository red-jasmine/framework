<?php

namespace RedJasmine\Support\Infrastructure;


use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;

/**
 * 全局上下文
 */
class ServiceContextManage
{


    public function __construct(protected $app)
    {

    }


    protected array $data = [];


    public function setOperator(UserInterface $operator) : static
    {
        $this->put('operator', $operator);
        return $this;
    }

    public function getOperator() : ?UserInterface
    {
        $operator = $this->get('operator');
        if ($operator) {
            $operator;
        } else {
            $app      = ($this->app)();
            $request  = $app->make('request');
            $operator = $request->user();

        }
        if ($operator) {
            if ($operator instanceof UserInterface) {
                return $operator;
            } else {
                // 获取模型名称 TODO
                return UserData::from([ 'id' => $operator->getKey(), 'type' => get_class($operator) ]);
            }
        }

        return $operator;
    }

    /**
     * @param $key
     *
     * @return mixed|void
     */
    public function get($key)
    {
        if (!isset($this->data[$key])) {
            return;
        }
        $item = $this->data[$key];
        if (is_callable($item)) {
            return $item();
        }
        return $item;
    }

    public function put($key, $value) : bool
    {
        $this->data[$key] = $value;
        return true;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function forget($key) : bool
    {
        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function flush() : bool
    {
        $this->data = [];

        return true;
    }

}
