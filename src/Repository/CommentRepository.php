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
    }
}