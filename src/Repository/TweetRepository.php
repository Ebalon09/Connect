<?php

/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 12.11.18
 * Time: 11:43
 */

namespace Test\Repository;

use Test\Model\Tweet;

class TweetRepository extends BaseRepository
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * TweetRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $result = $this->database->query("SELECT * FROM Tweet ORDER BY createDate DESC;");

        $tweets = [];
        foreach($result as $data) {
            $tweet = $this->arrayToObject($data);

            $tweets[] = $tweet;
        }
        return $tweets;
    }

    /**
     * @param $id
     * @return Tweet
     */
    public function findOneById($id)
    {
        $data = $this->database->query("SELECT * FROM Tweet WHERE id = :id;", [
            'id' => $id
        ])[0];

        $tweet = $this->arrayToObject($data);
        return $tweet;
    }

    /**
     * @return mixed
     */
    protected function getTableName()
    {
        return 'Tweet';
    }

    /**
     * @param Tweet $tweet
     * @return mixed
     */
    public function remove(Tweet $tweet)
    {
        $data = $this->objectToArray($tweet);
        $data2['id'] = $data['id'];

        $query = "DELETE FROM Tweet ";
        $query .= "WHERE id = :id";
        return $this->database->insert($query, $data2);
    }

    /**
     * @param $data
     * @return Tweet
     */
    protected function arrayToObject($data)
    {
        $tweet = new Tweet();
        $tweet->setId($data['id']);
        $tweet->setText($data['text']);
        $tweet->setDatum(new \DateTime($data['createDate']));
        $tweet->setUser($this->userRepository->findOneBy(['id' => $data['userid']]));
        $tweet->setDestination($data['destination']);
        $tweet->setLinkID($data['LinkID']);

        if($data['reTweet'] !== null) {

            $tweet->setReTweet($this->findOneBy(['id' => $data['reTweet']]));
        }
        return $tweet;
    }

    /**
     * @param Tweet $tweet
     * @return array
     */
    protected function objectToArray($tweet)
    {
        $data = [
            'id' => $tweet->getId(),
            'text' => $tweet->getText(),
            'createDate' => $tweet->getDatum()->format('Y-m-d H:i:s'),
            'userid' => $tweet->getUser()->getId(),
            'destination' => $tweet->getDestination(),
            'LinkID' => $tweet->getLinkID(),
            'reTweet' => $tweet->getReTweet(),
        ];
        return $data;
    }

    /**
     * @param mixed $model
     * @return bool
     */
    protected function isSupported($model)
    {
        return $model instanceof Tweet;
    }
}