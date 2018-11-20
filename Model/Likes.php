<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 13.11.18
 * Time: 16:12
 */

class Likes{

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