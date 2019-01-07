<?php

namespace Test\Events;

use Symfony\Component\EventDispatcher\Event;
use Test\Model\User;

/**
 * Class CreatedAccoutEvent
 *
 * @author Florian Stein <fstein@databay.de>
 */
class CreatedAccoutEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

    const NAME = 'Created.account';


    /**
     * CreatedAccoutEvent constructor.
     *
     * @param User $user
     */
    public function __construct (User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}