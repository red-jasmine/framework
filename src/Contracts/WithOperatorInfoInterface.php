<?php

namespace RedJasmine\Support\Contracts;

interface WithOperatorInfoInterface
{

    /**
     * @param UserInterface|null $operator
     * @return $this
     */
    public function setOperator(?UserInterface $operator = null) : self;

    /**
     * @param ClientInterface|null $client
     * @return $this
     */
    public function setClient(?ClientInterface $client = null) : self;

}
