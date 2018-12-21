<?php

namespace Test\Model;

/**
 * Class Tweet
 *
 * @author Florian Stein <fstein@databay.de>
 */
class Tweet
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var \DateTime
     */
    protected $datum;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var Likes
     */
    protected $likes;

    /**
     * @var string
     */
    protected $linkID;

    /**
     * @var Tweet
     */
    protected $reTweet;

    /**
     * @var int
     */
    protected $likeNumber;

    /**
     * @var int
     */
    protected $commentNumber;

    /**
     * @var Comment[]
     */
    protected $comments;

    /**
     * Tweet constructor.
     */
    public function __construct()
    {
        $this->datum = new \DateTime();
    }

    /**
     * @return Comment
     */
    public function getComments ()
    {
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     */
    public function setComments ($comments)
    {
        $this->commentNumber = count($comments);
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getLikenumber ()
    {
        return $this->likeNumber;
    }

    /**
     * @return int
     */
    public function getCommentnumber()
    {
        return $this->commentNumber;
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
        $this->likenumber = count($likes);
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
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * @param \DateTime $datum
     */
    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;
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