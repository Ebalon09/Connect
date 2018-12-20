<?php

namespace Test\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Model\Comment;
use Test\Repository\CommentRepository;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Session;
use Test\Services\Templating;

/**
 * Class CommentController
 *
 * @author Florian Stein <fstein@databay.de>
 */
class CommentController
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
     * @var CommentRepository
     */
    protected $commentRepository;

    /**
     * CommentController constructor.
     *
     * @param TweetRepository   $tweetRepository
     * @param LikeRepository    $likeRepository
     * @param UserRepository    $userRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct (
        TweetRepository $tweetRepository,
        LikeRepository $likeRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    ) {
        $this->tweetRepository = $tweetRepository;
        $this->likeRepository = $likeRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction (Request $request)
    {
        $allTweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);

        $commentarray = [];
        $likearray = [];
        foreach ($allTweets as $tweet) {
            $likearray[$tweet->getId()] = $this->likeRepository->countLikes($tweet);
            $commentarray[$tweet->getId()] = $this->commentRepository->countComments($tweet);
        }

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'        => $allTweets,
            'tweet'         => $this->tweetRepository->findOneBy(['id' => $request->query->get('tweet')]),
            'likes'         => $likes,
            'countLikes'    => $likearray,
            'user'          => $this->userRepository->currentUser(),
            'c'             => $request->query->get('c'),
            'countcomments' => $commentarray,
        ]));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function commentFeed (Request $request)
    {
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->query->get('tweet')]);

        $array = [];
        $array[$tweet->getId()] = $this->likeRepository->countLikes($tweet);

        return new Response(Templating::getInstance()->render('comment/commentFeed.html.twig', [
            'comments'   => $this->commentRepository->findBy(['tweetid' => $request->query->get('tweet')]),
            'tweet'      => $tweet,
            'likes'      => $this->likeRepository->findBy(['userid' => $_SESSION['userid']]),
            'countLikes' => $array,
            'user'       => $this->userRepository->currentUser(),
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function createAction (Request $request)
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->userRepository->currentUser();
            $tweet = $this->tweetRepository->findOneBy(['id' => $request->query->get('tweet'),]);

            $comment = new Comment();

            $comment->setComment(trim(strip_tags($request->get('text'))));
            $comment->setUser($user);
            $comment->setTweet($tweet);

            if ($request->get('text') != '') {
                $this->commentRepository->add($comment);

                $session = Session::getInstance();
                $session->write('success', 'Kommentar erfolgreich gepostet');
            } else {
                $session = Session::getInstance();
                $session->write('danger', 'Du kannst keinen leeren Kommentar posten!');
            }

            $id = $request->query->get('tweet');
            if ($request->query->get('c') == 1) {
                return new RedirectResponse("/feed/comments/$id/c");
            }

            return new RedirectResponse('/feed');
        }
    }

    /**
     * @param Request $request
     *
     * @return Response|RedirectResponse
     * @throws \Exception
     */
    public function updateAction (Request $request)
    {
        $comment = $this->commentRepository->findOneBy(['id' => $request->query->get('idc'),]);
        $user = $this->userRepository->currentUser();
        $tweet = $this->tweetRepository->findBy(['id' => $comment->getTweet()->getId()])[0];

        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->userRepository->findOneBy([
                'id' => $_SESSION['userid'],
            ]);

            $comment = new Comment();
            $comment->setComment(trim(strip_tags($request->get('text'))));
            $comment->setUser($user);
            $comment->setTweet($tweet);
            $comment->setId($request->query->get('idc'));

            $this->commentRepository->add($comment);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            $id = $comment->getTweet()->getId();
            $idc = $request->query->get('idc');
            if ($request->query->get('c') == true) {
                return new RedirectResponse("/feed/comments/$id/true/$idc");
            }

            return new RedirectResponse("/feed");
        }

        return new Response(Templating::getInstance()->render('comment/commentFeed.html.twig', [
            'result'   => $this->commentRepository->findBy(['tweetid' => $request->query->get('id')]),
            'update'   => $comment,
            'comments' => $this->commentRepository->findBy(['tweetid' => $tweet->getId()]),
            'user'     => $user,
            'id'       => $comment->getId(),
            'tweet'    => $tweet,
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction (Request $request)
    {
        $comment = $this->commentRepository->findOneBy([
            'id' => $request->query->get('idc'),
        ]);
        $id = $comment->getTweet()->getId();

        $this->commentRepository->remove($comment);

        $session = Session::getInstance();
        $session->write('success', 'Tweet erfolgreich gelöscht');

        $idc = $request->query->get('idc');
        if ($request->query->get('c') == true) {
            return new RedirectResponse("/feed/comments/$id/true/$idc");
        }

        return new RedirectResponse("/feed");
    }
}