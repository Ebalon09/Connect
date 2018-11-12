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
     * @var int
     */
    protected $postid;

    /**
     * @var string
     */
    protected $destination;

    public function __construct()
    {
        $this->datum = new DateTime();
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
     * @return int
     */
    public function getPostid()
    {
        return $this->postid;
    }

    /**
     * @param int $postid
     */
    public function setPostid($postid)
    {
        $this->postid = $postid;
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
}