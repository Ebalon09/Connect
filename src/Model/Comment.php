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
class Comment extends BaseModel
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
     * @ORM\ManyToOne(targetEntity="Tweet", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $Tweet;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
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