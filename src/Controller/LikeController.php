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
     * TwitterController constructor.
     */
    public function __construct ()
    {
        $this->tweetRepository = new TweetRepository();
        $this->userRepository = new UserRepository();
        $this->likeRepository = new LikeRepository();
    }

    /**
     * @return Response
     */
    public function indexAction ()
    {
        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);

        return new Response(Templating::getInstance()->render('twitterFeed.html.twig', [
            'result' => $tweets,
            'action' => "index.php?controller=TwitterController&action=createAction",
            'likes'  => $likes,
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function likeAction (Request $request)
    {
        $likes = $this->likeRepository->findBy([
            'userid' => $_SESSION['userid'],
        ]);

        foreach ((array)$likes as $data) {
            if ($data->getTweet()->getId() == $request->query->get('id')) {
                Session::getInstance()->write('danger', 'Bereits Geliked!');

                return new RedirectResponse('./index.php');
            }
        }
        $likes = new Likes();
        $likes->setUser($this->userRepository->findOneBy([
            'username' => $_SESSION['username'],
        ]));
        $likes->setTweet($this->tweetRepository->findOneBy([
            'id' => $request->query->get('id'),
        ]));
        $this->likeRepository->add($likes);

        $id = $request->query->get('id');
        if ($request->query->get('c') == true) {
            return new RedirectResponse("./index.php?controller=CommentController&action=indexAction&id=$id&c=true");
        }

        return new RedirectResponse("./index.php");
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function dislikeAction (Request $request)
    {
        $like = $this->likeRepository->findOneBy(['tweetid' => $request->query->get('id')]);
        $this->likeRepository->remove($like);

        $id = $request->query->get('id');
        if ($request->query->get('c') == true) {
            return new RedirectResponse("./index.php?controller=CommentController&action=indexAction&id=$id&c=true");
        }

        return new RedirectResponse('./index.php');
    }
}