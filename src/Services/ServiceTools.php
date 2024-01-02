<?php

namespace RedJasmine\Support\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HigherOrderTapProxy;
use RedJasmine\Support\Contracts\ClientInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Helpers\ClientObjectBuilder;

trait ServiceTools
{

    /**
     * 所属人
     * @var UserInterface|null
     */
    private ?UserInterface $owner = null;

    /**
     * 获取调用服务所属人
     * @return UserInterface|null
     */
    public function getOwner() : ?UserInterface
    {
        return $this->owner;
    }

    /**
     * @param UserInterface|null $owner
     *
     * @return static
     */
    public function setOwner(?UserInterface $owner) : static
    {
        $this->owner = $owner;
        return $this;
    }


    /**
     * 操作人
     * @var UserInterface|null
     */
    private ?UserInterface $operator = null;

    /**
     * 操作客户端
     * @var ClientInterface|null
     */
    private ?ClientInterface $client = null;


    /**
     * @return ?ClientInterface
     */
    public function getClient() : ?ClientInterface
    {
        return $this->client ?: $this->defaultClient();
    }

    /**
     * @param ClientInterface|null $client
     *
     * @return static
     */
    public function setClient(?ClientInterface $client = null) : static
    {
        $this->client = $client;
        return $this;
    }

    protected function defaultClient() : ?ClientInterface
    {
        return new ClientObjectBuilder(request());
    }


    /**
     * 获取操作人
     * @return UserInterface|null
     */
    public function getOperator() : ?UserInterface
    {
        return $this->operator ?: $this->defaultOperator();
    }

    /**
     * 设置操作人
     *
     * @param UserInterface|null $operator
     *
     * @return $this
     */
    public function setOperator(?UserInterface $operator = null): static
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * 默认操作人
     * @return UserInterface|null
     */
    protected function defaultOperator() : ?UserInterface
    {

        // 如果是 CLI 环境 那么设置为系统
        if (app()->runningInConsole()) {

        }
        // 如果是 web 环境 那么就需要设置为游客
        $user = Auth::user();
        if ($user instanceof UserInterface) {
            return $user;
        }
        foreach (Config::get('auth.guards') as $guard => $config) {
            $user = Auth::guard($guard)->user();
            if ($user instanceof UserInterface) {
                return $user;
            }

        }
        return null;
    }


    /**
     * 管理创建人
     *
     * @param mixed $model
     *
     * @return HigherOrderTapProxy|mixed
     */
    public function modelWithCreator(mixed $model) : mixed
    {
        return tap($model, function ($model) {
            if ($this->getOperator()) {
                $model->creator_type     = $this->getOperator()->getUserType();
                $model->creator_id      = $this->getOperator()->getID();
                $model->creator_nickname = $this->getOperator()->getNickname();
            }
        });
    }

    /**
     * 管理更新者
     *
     * @param mixed $model
     *
     * @return HigherOrderTapProxy|mixed
     */
    public function modelWithUpdater(mixed $model) : mixed
    {
        return tap($model, function ($model) {
            if ($this->getOperator()) {
                $model->updater_type     = $this->getOperator()->getUserType();
                $model->updater_id      = $this->getOperator()->getID();
                $model->updater_nickname = $this->getOperator()->getNickname();
            }
        });
    }


    public function make():static
    {
        return new static();
    }


}
