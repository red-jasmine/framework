<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

interface ReadRepositoryInterface
{

    public function setAllowedFilters(?array $allowedFilters) : static;

    public function setAllowedIncludes(?array $allowedIncludes) : static;

    public function setAllowedFields(?array $allowedFields) : static;

    public function setAllowedSorts(?array $allowedSorts) : static;

    public function setQueryCallbacks(array $queryCallbacks) : static;


}
