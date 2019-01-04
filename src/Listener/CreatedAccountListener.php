<?php

namespace Test\Listener;

/**
 * Class AccountCreatedListener
 *
 * @author Florian Stein <fstein@databay.de>
 */
class CreatedAccountListener
{
    public function sendAccountCreatedMail()
    {
        var_dump("mail send");exit;
    }

}