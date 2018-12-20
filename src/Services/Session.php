<?php

namespace Test\Services;

require_once 'SingletonTrait.php';

/**
 * Class Session
 *
 * @author Florian Stein <fstein@databay.de>
 */
class Session
{
    use SingletonTrait;

    /**
     * @return array
     */
    public function readMessage()
    {
        $messages = [];

        if(isset($_SESSION['messages']))
        {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }

        return $messages;
    }


    /**
     * @param $type
     * @param $message
     */
    public function write($type, $message)
    {
        if(!isset($_SESSION['messages']))
        {
            $_SESSION['messages'] = [];
        }
        $_SESSION['messages'][$type][] = $message;
    }
}
