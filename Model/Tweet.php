<?php

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
     * Tweet constructor.
     */
    public function __construct()
    {
        $this->datum = new DateTime();
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
     * @param Likes $likes
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
     * @return DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * @param DateTime $datum
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