<?php

namespace Test\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tweet
 *
 * @author Florian Stein <fstein@databay.de>
 *
 * @ORM\Entity()
 * @ORM\Table(name="tweets")
 */
class Tweet extends BaseModel
{

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $destination;

    /**
     * @var Likes[]
     *
     * @ORM\OneToMany(targetEntity="Likes", mappedBy="tweet")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $likes;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $linkID;

    /**
     * @var Tweet
     *
     * @ORM\ManyToOne(targetEntity="Tweet")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $reTweet;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="Tweet")
     */
    protected $comments;

    /**
     * Tweet constructor.
     */
    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->likes      = new ArrayCollection();
        $this->comments   = new ArrayCollection();
    }

    /**
     * @return Comment
     */
    public function getComments ()
    {
        return $this->comments;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate ()
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $createDate
     */
    public function setCreateDate (\DateTime $createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @param Comment[] $comments
     */
    public function setComments ($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getNumberOfLikes ()
    {
        return \count($this->likes);
    }

    /**
     * @return int
     */
    public function getNumberOfComments()
    {
        return \count($this->comments);
    }

    /**
     * @return Tweet
     */
    public function getReTweet()
    {
        return $this->reTweet;
    }

    /**
     * @param Tweet $tweet
     */
    public function setReTweet($tweet)
    {
        $this->reTweet = $tweet;
    }

    /**
     * @return string
     */
    public function getLinkID()
    {
        return $this->linkID;
    }

    /**
     * @param string $linkID
     */
    public function setLinkID($linkID)
    {
        $this->linkID = $linkID;
    }

    /**
     * @return Likes
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param Likes[] $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
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
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function __toString(){
        return "$this->id";
    }



    /**
     * @param Likes $likes[]
     * @return bool
     */
    public function isLikedByUser($likes)
    {
        foreach((array)$likes as $data)
        {
            if($data->getTweet()->getId() === $this->getId()){
                return true;
            }
        }
        return false;
    }
}