<?php

namespace RedJasmine\Support\Services;

use RedJasmine\Support\Contracts\ClientInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\User\Services\UserUpdateService;

/**
 * 操作服务
 */
trait OperatorActionService
{
    /**
     * 操作人
     * @var UserInterface
     */
    protected UserInterface $operator;

    /**
     * 操作客户端
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * @return UserInterface
     */
    public function getOperator() : UserInterface
    {
        return $this->operator;
    }

    /**
     * @param UserInterface $operator
     * @return $this
     */
    public function setOperator(UserInterface $operator) : self
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getClient() : ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     * @return $this
     */
    public function setClient(ClientInterface $client) : self
    {
        $this->client = $client;
        return $this;
    }


}
