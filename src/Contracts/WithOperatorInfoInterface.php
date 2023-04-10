<?php

namespace RedJasmine\Support\Contracts;

interface WithOperatorInfoInterface
{

    /**
     * @param UserInterface $operator
     * @return self
     */
    public function setOperator(UserInterface $operator) : self;

    /**
     * @param ClientInterface $client
     * @return self
     */
    public function setClient(ClientInterface $client) : self;

}
