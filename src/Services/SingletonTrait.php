<?php
/**
 * Created by PhpStorm.
 * User: jgeiger
 * Date: 12.10.18
 * Time: 11:23
 */
namespace Test\Services;

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