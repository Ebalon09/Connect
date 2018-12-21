<?php

namespace Test\Services;

use Symfony\Component\HttpFoundation\Request;
use Test\Model\Tweet;
use Test\Repository\CommentRepository;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;

/**
 * Class TweetService
 *
 * @author Florian Stein <fstein@databay.de>
 */
class TweetService
{

    /**
     * @var TweetRepository
     */
    protected $tweetRepository;

    /**
     * @var LikeRepository
     */
    protected $likeRepository;

    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    /**
     * TweetService constructor.
     *
     * @param TweetRepository   $tweetRepository
     * @param LikeRepository    $likeRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(TweetRepository $tweetRepository, LikeRepository $likeRepository, CommentRepository $commentRepository)
    {
        $this->tweetRepository        = $tweetRepository;
        $this->likeRepository         = $likeRepository;
        $this->commentRepository      = $commentRepository;
    }

    /**
     * @return \Test\Model\Tweet[]
     */
    public function loadFullTweets()
    {
        /**
         * @var Tweet[] $tweets
         */
        $tweets = $this->tweetRepository->findAll();
        foreach ($tweets as $tweet) {
            $likes = $this->likeRepository->TweetLikes($tweet);
            $tweet->setLikes($likes);
            $comments = $this->commentRepository->TweetComments($tweet);

            $tweet->setComments($comments);
        }
        return $tweets;
    }

}
