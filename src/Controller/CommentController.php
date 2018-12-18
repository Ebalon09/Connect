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
     */
    public function __construct ()
    {
        $this->tweetRepository = new TweetRepository();
        $this->likeRepository = new LikeRepository();
        $this->userRepository = new UserRepository();
        $this->commentRepository = new CommentRepository();
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction (Request $request)
    {

        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);
        $comments = $this->commentRepository->findBy(['tweetid' => $request->query->get('id')]);
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->query->get('id')]);
        $user = $this->userRepository->currentUser();

        $array = [];
        $array[$tweet->getId()] = $this->likeRepository->countLikes($tweet);

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'comments'   => $comments,
            'result'     => $tweets,
            'tweet'      => $tweet,
            'likes'      => $likes,
            'countLikes' => $array,
            'user'       => $user,
            'userid' => $_SESSION['userid'],
            'username' => $_SESSION['username'],
            'userimage' => $user->getPicture(),
            'c' => $_GET['c'],
        ]));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function commentFeed (Request $request)
    {
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);
        $comments = $this->commentRepository->findBy(['tweetid' => $request->query->get('id')]);
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->query->get('id')]);

        $user = $this->userRepository->currentUser();

        $array = [];
        $array[$tweet->getId()] = $this->likeRepository->countLikes($tweet);

        return new Response(Templating::getInstance()->render('comment/commentFeed.html.twig', [
            'comments'   => $comments,
            'tweet'      => $tweet,
            'likes'      => $likes,
            'countLikes' => $array,
            'user'       => $user,
            'userid' => $_SESSION['userid'],
            'username' => $_SESSION['username'],
            'userimage' => $user->getPicture(),
            'tweetuserimage' => $tweet->getUser()->getPicture(),
            'tweettext' => $tweet->getText(),
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
            $tweet = $this->tweetRepository->findOneBy([
                'id' => $request->query->get('id'),
            ]);

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

            $id = $request->query->get('id');
            if ($request->query->get('c') == true) {
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
        $comment = $this->commentRepository->findOneBy([
            'id' => $request->query->get('idc'),
        ]);
        $user = $this->userRepository->currentUser();
        $tweet = $this->tweetRepository->findBy(['id' => $comment->getTweet()->getId()])[0];
        $comments = $this->commentRepository->findBy(['tweetid' => $tweet->getId()]);

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
            'comments' => $comments,
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