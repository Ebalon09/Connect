<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 13.11.18
 * Time: 16:12
 */
namespace Test\Repository;


use Test\Model\Likes;
use Test\Model\Tweet;

class LikeRepository extends BaseRepository
{
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var TweetRepository
     */
    protected $tweetRepository;
    /**
     * LikeRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->tweetRepository = new TweetRepository();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $result = $this->database->query("SELECT * FROM Likes");

        $tweets = [];
        foreach($result as $data)
        {
            $tweet = $this->arrayToObject($data);

            $tweets[] = $tweet;
        }
        return $tweets;
    }

    /**
     * @param Likes $likes
     * @return mixed
     */
    public function add(Likes $likes)
    {
        $data = $this->objectToArray($likes);

        $properties = [];
        foreach($data as $key => $value)
        {
            if($key !== 'id')
            {
                $properties[$key] = $key . ' = :' .$key;
            }
        }
        $query = "INSERT INTO Likes SET ";
        $query .= \join(', ', $properties);

        unset($data['id']);
        return $this->database->insert($query, $data);
    }

    /**
     * @param Likes $likes
     * @return mixed
     */
    public function remove(Likes $likes)
    {
        $data = $this->objectToArray($likes);
        $data2['id'] = $data['id'];

        $query = "DELETE FROM Likes ";
        $query .= "WHERE id = :id";

        return $this->database->insert($query, $data2);
    }

    /**
     * @param $data
     * @return Likes
     */
    protected function arrayToObject($data)
    {
        $likes = new Likes();
        $likes->setId($data['id']);
        $likes->setUser($this->userRepository->findOneBy(['id' => $data['userid']]));
        $likes->setTweet($this->tweetRepository->findOneById($data['tweetid']));

        return $likes;
    }

    /**
     * @param Likes $model
     * @return array
     */
    protected function objectToArray($model)
    {
        $data = [
            'id' => $model->getId(),
            'userid' => $model->getUser()->getId(),
            'tweetid' => $model->getTweet()->getId()
        ];

        return $data;
    }

    /**
     * @return mixed
     */
    protected function getTableName()
    {
        return 'Likes';
    }

    /**
     * @param $model
     * @return bool
     */
    protected function isSupported($model)
    {
        return $model instanceof Likes;
    }

    /**
     * @param Tweet $tweet
     * @return int
     */
    public function countLikes(Tweet $tweet)
    {
        return count($this->database->query("SELECT * FROM Likes WHERE tweetid = :tweetid",[
            'tweetid' => $tweet->getId(),
        ]));
    }

}