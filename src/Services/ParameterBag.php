<?php

namespace Test\Services;

class ParameterBag
{
    /**
     * @var array
     */
    private $elements;

    /**
     * ParameterBag constructor.
     *
     * @param array $elements
     */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * Get the value of the specific key. Throws an exception if the key does not exists.
     *
     * @param string $key
     *
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function get($key)
    {
        if(!$this->hasKey($key))
        {
            return false;                                      //returns the given array-key
        }
        return $this->elements[$key];
    }

    /**
     * Checks if a specified key exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasKey($key)
    {
        return isset($this->elements[$key]);                    //checks for an array-key
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

}