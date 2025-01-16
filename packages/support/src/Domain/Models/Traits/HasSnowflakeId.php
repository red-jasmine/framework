<?php

namespace RedJasmine\Support\Domain\Models\Traits;

use Exception;
use RedJasmine\Support\Helpers\ID\Snowflake;

trait HasSnowflakeId
{


    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeHasSnowflakeId() : void
    {
        $this->usesUniqueIds = true;
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds() : array
    {
        return [$this->getKeyName()];
    }

    /**
     * Generate a new SnowflakeId for the model.
     *
     * @return int
     * @throws Exception
     */
    public function newUniqueId() : int
    {

        $hasUniqueShortId = property_exists($this, 'uniqueShortId') && (bool) $this->uniqueShortId;
        return $hasUniqueShortId ? Snowflake::shortId() : Snowflake::buildId();
    }


    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing() : bool
    {
        return false;
    }

}
