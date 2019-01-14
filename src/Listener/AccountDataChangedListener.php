<?php

namespace Test\Listener;

use Test\Events\AccountDataChangedEvent;

/**
 * Class AccountDataChangedListener
 *
 * @author Florian Stein <fstein@databay.de>
 */
class AccountDataChangedListener
{
    public function sendDataUpdatedMail(AccountDataChangedEvent $event)
    {
        //var_dump("Hi " . $event->getUser()->getUsername() . " ! Your Account Data has been updated.");exit;
    }

}