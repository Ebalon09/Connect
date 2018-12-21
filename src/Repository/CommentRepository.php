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
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var TweetRepository
     */
    protected $tweetRepository;

    /**
     * CommentRepository constructor.
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
     * @return Comment
     */
    protected function arrayToObject ($data)
    {
        $comment = new Comment();
        $comment->setId($data['id']);
        $comment->setUser($this->userRepository->findOneBy(['id' => $data['userid']]));
        $comment->setTweet($this->tweetRepository->findOneBy(['id' => $data['tweetid']]));
        $comment->setComment($data['comment']);

        return $comment;
    }

    /**
     * @param Comment $model
     *
     * @return array
     */
    protected function objectToArray ($model)
    {
        $data = [
            'id'      => $model->getId(),
            'userid'  => $model->getUser()->getId(),
            'tweetid' => $model->getTweet()->getId(),
            'comment' => $model->getComment(),
        ];

        return $data;
    }

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
        return 'Comments';
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
        return count($this->database->query("SELECT * FROM Comments WHERE tweetid = :tweetid", [
            'tweetid' => $tweet->getId(),
        ]));
    }


}