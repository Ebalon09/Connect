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
     * @return mixed
     */
    protected function getTableName ()
    {
        return 'likes';
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
        return $this->count($this->findBy(['likes' => $tweet->getLikes()]));
    }

    /**
     * @param Tweet $tweet
     *
     * @return array
     */
    public function TweetLikes(Tweet $tweet)
    {
        $likes = $this->findBy(['tweetid' => $tweet->getId()]);
        return $likes;
    }


}