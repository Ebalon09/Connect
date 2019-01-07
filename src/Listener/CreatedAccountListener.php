<?php

namespace Test\Listener;

use Test\Events\CreatedAccoutEvent;

/**
 * Class AccountCreatedListener
 *
 * @author Florian Stein <fstein@databay.de>
 */
class CreatedAccountListener
{
    public function sendAccountCreatedMail(CreatedAccoutEvent $event)
    {
        var_dump("mail send to " . $event->getUser()->getEmail());exit;
    }

}