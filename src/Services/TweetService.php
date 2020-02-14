<?php

namespace Test\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Test\Model\Likes;
use Test\Model\Tweet;
use Test\Model\User;
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
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->tweetRepository        = $manager->getRepository(Tweet::class);
        $this->likeRepository         = $manager->getRepository(Likes::class);
        $this->commentRepository      = $manager->getRepository(User::class);
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
            $comments = $this->commentRepository->TweetComments($tweet);

            $tweet->setComments($comments);
        }
        return $tweets;
    }

}
