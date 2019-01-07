<?php

namespace Test\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Test\Events\ChangeDataEvent;

/**
 * Class ChangeDataListener
 *
 * @author Florian Stein <fstein@databay.de>
 */
class ChangeDataListener
{
    public function checkAuthority(ChangeDataEvent $event)
    {
        if($event->getTweet()->getUser()->getId() != $_SESSION['userid'])
        {
            return new RedirectResponse("/error");
        }
        if($event->getComment()->getUser()->getId() != $_SESSION['userid'])
        {
            return new RedirectResponse("/error");
        }



    }

}