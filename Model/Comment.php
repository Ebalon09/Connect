<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 26.11.18
 * Time: 17:43
 */
namespace Test\Model;

class Comment
{
    /**
     * @var string
     */
    protected $comment;
    /**
     * @var Tweet
     */
    protected $Tweet;
    /**
     * @var int
     */
    protected $id;
    /**
     * @var User
     */
    protected $user;
    /**
     * @return String
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Tweet
     */
    public function getTweet()
    {
        return $this->Tweet;
    }

    /**
     * @param Tweet $Tweet
     */
    public function setTweet($Tweet)
    {
        $this->Tweet = $Tweet;
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
}