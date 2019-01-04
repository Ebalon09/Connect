<?php

namespace Test\Repository;

use Test\Model\Comment;
use Test\Model\Tweet;

/**
 * Class CommentRepository
 *
 * @author Florian Stein <fstein@databay.de>
 */
class CommentRepository extends BaseRepository
{
    ///**
    // * @var UserRepository
    // */
    //protected $userRepository;
    //
    ///**
    // * @var TweetRepository
    // */
    //protected $tweetRepository;
    //
    ///**
    // * @var LikeRepository
    // */
    //protected $likeRepository;
    //
    ///**
    // * CommentRepository constructor.
    // *
    // * @param UserRepository  $userRepository
    // * @param TweetRepository $tweetRepository
    // * @param LikeRepository  $likeRepository
    // */
    //public function __construct (UserRepository $userRepository, TweetRepository $tweetRepository, LikeRepository $likeRepository)
    //{
    //    parent::__construct();
    //    $this->userRepository = $userRepository;
    //    $this->tweetRepository = $tweetRepository;
    //    $this->likeRepository = $likeRepository;
    //}

    /**
     * @param Tweet $tweet
     *
     * @return array
     */
    public function TweetComments(Tweet $tweet){
        $comments = $this->findBy(['tweetid' => $tweet->getId()]);
        return $comments;
    }

    /**
     * @return string
     */
    protected function getTableName ()
    {
        return 'comments';
    }

    /**
     * @param $model
     *
     * @return bool
     */
    protected function isSupported ($model)
    {
        return $model instanceof Comment;
    }

    /**
     * @param Tweet $tweet
     *
     * @return int
     */
    public function countComments (Tweet $tweet)
    {


        return $this->count($this->findBy(['comments' => $tweet->getComments()]));


        //return count($this->database->query("SELECT * FROM Comments WHERE tweetid = :tweetid", [
        //    'tweetid' => $tweet->getId(),
        //]));
    }
}