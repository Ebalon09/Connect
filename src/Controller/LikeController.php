<?php

namespace Test\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Model\Likes;
use Test\Model\Tweet;
use Test\Model\User;
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
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * LikeController constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct (
        EntityManagerInterface $manager
    ) {
        $this->tweetRepository = $manager->getRepository(Tweet::class);
        $this->userRepository = $manager->getRepository(User::class);
        $this->likeRepository = $manager->getRepository(Likes::class);
        $this->manager = $manager;
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
        $likes->setUser($this->userRepository->findOneBy(['id' => $_SESSION['userid']]));
        $likes->setTweet($this->tweetRepository->findOneBy([
            'id' => $request->get('tweet'),
        ]));

        $this->manager->persist($likes);
        $this->manager->flush();

        return new RedirectResponse("/feed");
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function dislikeAction (Request $request)
    {
        $like = $this->likeRepository->findOneBy(['tweet' => $request->get('tweet')]);

        $this->manager->remove($like);
        $this->manager->flush();

        return new RedirectResponse('/feed');
    }
}