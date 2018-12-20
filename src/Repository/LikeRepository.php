<?php

namespace Test\Repository;

use Test\Model\Likes;
use Test\Model\Tweet;

/**
 * Class LikeRepository
 *
 * @author Florian Stein <fstein@databay.de>
 */
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
     *
     * @param UserRepository  $userRepository
     * @param TweetRepository $tweetRepository
     */
    public function __construct (UserRepository $userRepository, TweetRepository $tweetRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->tweetRepository = $tweetRepository;
    }

    /**
     * @param $data
     *
     * @return Likes
     */
    protected function arrayToObject ($data)
    {
        $likes = new Likes();
        $likes->setId($data['id']);
        $likes->setUser($this->userRepository->findOneBy(['id' => $data['userid']]));
        $likes->setTweet($this->tweetRepository->findOneBy(['id' => $data['tweetid']]));

        return $likes;
    }

    /**
     * @param Likes $model
     *
     * @return array
     */
    protected function objectToArray ($model)
    {
        $data = [
            'id'      => $model->getId(),
            'userid'  => $model->getUser()->getId(),
            'tweetid' => $model->getTweet()->getId(),
        ];

        return $data;
    }

    /**
     * @return mixed
     */
    protected function getTableName ()
    {
        return 'Likes';
    }

    /**
     * @param $model
     *
     * @return bool
     */
    protected function isSupported ($model)
    {
        return $model instanceof Likes;
    }

    /**
     * @param Tweet $tweet
     *
     * @return int
     */
    public function countLikes (Tweet $tweet)
    {
        return count($this->database->query("SELECT * FROM Likes WHERE tweetid = :tweetid", [
            'tweetid' => $tweet->getId(),
        ]));
    }

}