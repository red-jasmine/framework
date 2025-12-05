<?php

namespace RedJasmine\Support\Foundation\Context;


use Illuminate\Support\Facades\Auth;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\UserData;

/**
 * 全局上下文
 * TODO 改写
 */
class ServiceContextManage
{


    protected array $data = [];

    public function __construct(protected $app)
    {

    }

    public function setOperator(UserInterface $operator) : static
    {
        $this->put('operator', $operator);
        return $this;
    }

    public function put($key, $value) : bool
    {
        $this->data[$key] = $value;
        return true;
    }

    public function getOperator() : ?UserInterface
    {
        $operator = $this->get('operator') ?: $this->getAuthUser();
        if (!$operator) {
            return null;
        }
        if ($operator instanceof UserInterface) {
            return $operator;
        } else {
            return UserData::from([
                'id'   => $operator->getKey(),
                'type' => get_class($operator)
            ])->additional(['user' => $operator]);
        }
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

    /**
     * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getAuthUser()
    {
        return Auth::user();
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
