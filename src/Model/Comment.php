<?php

namespace Test\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Comment
 *
 * @author Florian Stein <fstein@databay.de>
 *
 * @ORM\Entity()
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @var Tweet
     *
     * @ORM\ManyToMany(targetEntity="Tweet")
     */
    protected $Tweet;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToMany(targetEntity="User")
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