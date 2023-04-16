<?php

namespace RedJasmine\Support\Services;

use RedJasmine\Support\Contracts\ClientInterface;
use RedJasmine\Support\Contracts\UserInterface;
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
    protected ?UserInterface $operator;

    /**
     * 操作客户端
     * @var ClientInterface|null
     */
    protected ?ClientInterface $client;

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
    public function make(?UserInterface $operator = null, ?ClientInterface $client = null) : self
    {

        $this->setOperator($operator);
        $this->setClient($client);
        return $this;
    }

}
