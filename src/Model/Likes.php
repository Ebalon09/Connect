<?php

namespace Test\Model;

/**
 * Class Likes
 *
 * @author Florian Stein <fstein@databay.de>
 */
class Likes
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Tweet
     */
    protected $tweet;

    /**
     * @var int
     */
    protected $likes;

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Tweet
     */
    public function getTweet()
    {
        return $this->tweet;
    }

    /**
     * @param Tweet $tweet
     */
    public function setTweet($tweet)
    {
        $this->tweet = $tweet;
    }





}