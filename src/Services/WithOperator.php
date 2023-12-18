<?php

namespace RedJasmine\Support\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\HigherOrderTapProxy;
use RedJasmine\Support\Contracts\ClientInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Contracts\WithOperatorInfoInterface;
use RedJasmine\User\Services\UserUpdateService;

/**
 * 操作服务
 */
trait WithOperator
{
    /**
     * 操作人
     * @var UserInterface|null
     */
    protected ?UserInterface $operator = null;

    /**
     * 操作客户端
     * @var ClientInterface|null
     */
    protected ?ClientInterface $client = null;

    /**
     * @return UserInterface|null
     */
    public function getOperator() : ?UserInterface
    {
        return $this->operator;
    }

    /**
     * @param UserInterface|null $operator
     * @return $this
     */
    public function setOperator(?UserInterface $operator = null) : self
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return ?ClientInterface
     */
    public function getClient() : ?ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface|null $client
     * @return $this
     */
    public function setClient(?ClientInterface $client = null) : self
    {
        $this->client = $client;
        return $this;
    }
    /**
     * @param UserInterface|null $operator
     * @param ClientInterface|null $client
     * @return $this
     */
    public function make(?UserInterface $operator = null, ?ClientInterface $client = null) : static
    {

        $this->setOperator($operator);
        $this->setClient($client);
        return $this;
    }


    /**
     * 管理创建人
     * @param mixed $model
     * @return HigherOrderTapProxy|mixed
     */
    public function modelWithCreator(mixed $model) : mixed
    {
        return tap($model, function ($model) {
            if ($this->getOperator()) {
                $model->creator_type     = $this->getOperator()->getUserType();
                $model->creator_id      = $this->getOperator()->getUID();
                $model->creator_nickname = $this->getOperator()->getNickname();
            }
        });
    }

    /**
     * 管理创建人
     * @param mixed $model
     * @return HigherOrderTapProxy|mixed
     */
    public function modelWithUpdater(mixed $model) : mixed
    {
        return tap($model, function ($model) {
            if ($this->getOperator()) {
                $model->updater_type     = $this->getOperator()->getUserType();
                $model->updater_id      = $this->getOperator()->getUID();
                $model->updater_nickname = $this->getOperator()->getNickname();
            }
        });
    }



    /**
     * @param $service
     * @return mixed
     */
    protected function getService($service) : mixed
    {
        $service = App::make($service);
        if ($service instanceof WithOperatorInfoInterface) {
            $service->setClient($this->getClient())->setOperator($this->getOperator());
        }
        return $service;
    }


}
