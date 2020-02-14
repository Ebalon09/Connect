<?php

namespace Test\Services;

/**
 * Class SingletonTrait
 *
 * @author Florian Stein <fstein@databay.de>
 */
trait SingletonTrait
{

    protected static $instance;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @return SingletonTrait
     */
    public static function getInstance()
    {
        if( ! self::$instance )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
