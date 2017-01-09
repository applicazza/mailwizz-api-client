<?php

namespace Applicazza\MailwizzApiClient\Contracts;

use ArrayAccess;

/**
 * Class AbstractContract
 * @package Applicazza\MailwizzApiClient\Contracts
 */
abstract class AbstractContract
{
    /**
     * Campaign constructor.
     * @param array $data
     */
    function __construct(array $data = [])
    {
        if (!empty($data))
            $this->fill($data);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function fill(array $data = [])
    {
        $properties = array_keys(get_class_vars(static::class));

        foreach ($data as $key => $value) {

            if (in_array($key, $properties))
                $this->$key = $value;

        }

        return $this;
    }
}