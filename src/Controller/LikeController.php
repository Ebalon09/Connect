<?php

namespace Test\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Model\Likes;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Session;
use Test\Services\Templating;

/**
 * Class LikesController
 *
 * @author Florian Stein <fstein@databay.de>
 */
class LikeController
{

    /**
     * @var TweetRepository
     */
    protected $tweetRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var LikeRepository
     */
    protected $likeRepository;

    /**
     * LikeController constructor.
     *
     * @param TweetRepository $tweetRepository
     * @param UserRepository  $userRepository
     * @param LikeRepository  $likeRepository
     */
    public function __construct (
        TweetRepository $tweetRepository,
        UserRepository $userRepository,
        LikeRepository $likeRepository
    ) {
        $this->tweetRepository = $tweetRepository;
        $this->userRepository = $userRepository;
        $this->likeRepository = $likeRepository;
    }

    /**
     * @return Response
     */
    public function indexAction ()
    {
        return new Response(Templating::getInstance()->render('twitterFeed.html.twig', [
            'result' => $this->tweetRepository->findAll(),
            'action' => "index.php?controller=TwitterController&action=createAction",
            'likes'  => $this->likeRepository->findBy(['userid' => $_SESSION['userid']]),
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function likeAction (Request $request)
    {
        $likes = new Likes();
        $likes->setUser($this->userRepository->currentUser());
        $likes->setTweet($this->tweetRepository->findOneBy([
            'id' => $request->query->get('tweet'),
        ]));
        $this->likeRepository->add($likes);

        return new RedirectResponse("/feed");
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function dislikeAction (Request $request)
    {
        $like = $this->likeRepository->findOneBy(['tweetid' => $request->query->get('tweet')]);
        $this->likeRepository->remove($like);

        return new RedirectResponse('/feed');
    }
}