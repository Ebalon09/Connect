<?php

namespace Test\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Test\Model\User;

/**
 * Class AccountDataChangedEvent
 *
 * @author Florian Stein <fstein@databay.de>
 */
class AccountDataChangedEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Request
     */
    protected $request;

    const NAME = 'AccountSettings.Changed';

    /**
     * AccountDataChangedEvent constructor.
     *
     * @param User    $user
     * @param Request $request
     */
    public function __construct (User $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->getRequest();
    }
}