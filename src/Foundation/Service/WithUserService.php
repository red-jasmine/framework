<?php

namespace RedJasmine\Support\Foundation\Service;

use Closure;
use RedJasmine\Support\Contracts\UserInterface;

trait WithUserService
{
    // TODO 采用全局管理 操作人员 子服务 不用再次嵌套 用山下文保存
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
        if ($this->owner) {
            return $this->owner;
        }
        if ($callback = $this->withOwner) {
            return $callback();
        }
        return null;
    }

    protected ?Closure $withOwner = null;

    public function setWithOwner(Closure $closure) : static
    {
        $this->withOwner = $closure;
        return $this;
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


    protected ?Closure $withOperator = null;

    public function setWithOperator(Closure $closure) : static
    {
        $this->withOperator = $closure;
        return $this;
    }

    /**
     * 操作人
     * @var UserInterface|null
     */
    private ?UserInterface $operator = null;

    public function getOperator() : ?UserInterface
    {
        if ($this->operator) {
            return $this->operator;
        }
        if ($callback = $this->withOperator) {
            return $callback();
        }
        return $this->operator;
    }

    public function setOperator(?UserInterface $operator) : static
    {
        $this->operator = $operator;
        return $this;
    }


}
