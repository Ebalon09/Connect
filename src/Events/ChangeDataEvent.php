<?php

namespace Test\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Test\Model\Comment;
use Test\Model\Tweet;
use Test\Model\User;

/**
 * Class ChangeDataEvent
 *
 * @author Florian Stein <fstein@databay.de>
 */
class ChangeDataEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Tweet
     */
    protected $tweet;

    /**
     * @var Comment
     */
    protected $comment;

    const NAME = 'Authority.Check';

    /**
     * ChangeDataEvent constructor.
     *
     * @param User    $user
     * @param Request $request
     * @param Tweet   $tweet
     * @param Comment $comment
     */
    public function __construct (User $user, Request $request, Tweet $tweet, Comment $comment)
    {
        $this->user = $user;
        $this->request = $request;
        $this->tweet = $tweet;
        $this->comment = $comment;
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

    /**
     * @return Tweet
     */
    public function getTweet()
    {
        return $this->tweet;
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }
}